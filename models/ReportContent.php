<?php

namespace humhub\modules\reportcontent\models;

use humhub\components\ActiveRecord;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\permissions\ManageContent;
use humhub\modules\reportcontent\components\ActiveQueryReportContent;
use humhub\modules\reportcontent\helpers\Permission;
use humhub\modules\reportcontent\notifications\NewReportAdmin;
use humhub\modules\space\models\Membership;
use humhub\modules\user\models\Group;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\models\Content;
use Yii;
use yii\base\InvalidArgumentException;

/**
 * This is the model class for table "report_content".
 *
 * The followings are the available columns in table 'report_content':
 * @property int $id
 * @property int $content_id
 * @property int $comment_id
 * @property int $reason
 * @property string $created_at
 * @property int $created_by
 * @property bool $system_admin_only
 *
 * @property User $user
 * @property Content $content
 */
class ReportContent extends ActiveRecord
{
    public const REASON_NOT_BELONG = 1;
    public const REASON_OFFENSIVE = 2;
    public const REASON_SPAM = 3;
    public const REASON_INCORRECT = 4;
    public const REASON_FILTER = 10;

    /**
     *
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'report_content';
    }


    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['content_id', 'reason'], 'required'],
            [['reason'], function ($attribute, $params, $validator) {
                $content = Content::findOne(['id' => $this->content_id]);
                $user = User::findOne(['id' => $this->created_by]);
                if (!$content || !$user || !$content->canView($user)) {
                    throw new InvalidArgumentException('Content or User cannot be null and must be visible!');
                }

                if (!empty($this->comment_id)) {
                    $comment = Comment::findOne(['id' => $this->comment_id]);
                    if (!$comment) {
                        throw new InvalidArgumentException('Comment not found!');
                    }
                    if (!Permission::canReportComment($comment, $user)) {
                        $this->addError('reason', 'You cannot report this comment!');
                    }
                } elseif (!Permission::canReportContent($content->getModel(), $user)) {
                    $this->addError('reason', 'You cannot report this content!');
                }
            }],
        ];
    }


    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'reason' => Yii::t('ReportcontentModule.base', 'For what reason do you want to report this content?'),
        ];
    }

    public function beforeSave($insert)
    {
        $content = Content::findOne(['id' => $this->content_id]);
        $contentContainer = $content->container;

        // If we report a space admin post, we create a system admin only report (only visible in admin area)
        /** @var Space $contentContainer */
        if ($contentContainer instanceof Space) {
            $membership = $contentContainer->getMembership($content->created_by);

            if ($membership && $membership->isPrivileged()) {
                $this->system_admin_only = true;
            }
        }

        if (!empty($this->comment_id)) {
            /** @var Comment $comment */
            $comment = Comment::find()->where(['id' => $this->comment_id])->one();
            if (!$comment || $comment->getContent()->id != $this->content_id) {
                throw new \Exception('Specified comment is not linked to given content');
            }
        }

        $noCreator = empty($this->created_by);
        $beforeSave = parent::beforeSave($insert);
        if ($noCreator) {
            $this->created_by = null;
        }
        return $beforeSave;
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if (empty($this->system_admin_only) && $this->content->container instanceof Space) {
                $query = Membership::getSpaceMembersQuery($this->content->container)
                    ->andWhere(['IN', 'group_id', [Space::USERGROUP_OWNER, Space::USERGROUP_ADMIN, Space::USERGROUP_MODERATOR]]);
            } else {
                $query = Group::getAdminGroup()->getUsers();
            }

            $notification = new NewReportAdmin();
            $notification->source = $this;
            $notification->originator = (!empty($this->created_by)) ? User::findOne(['id' => $this->created_by]) : null;
            $notification->sendBulk($query);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new ActiveQueryReportContent(get_called_class());
    }

    public function getReason(): string
    {
        return $this->getReasons()[$this->reason] ?? '';
    }

    public function getReasons($selectable = false): array
    {
        $reasons = [];

        if ($this->content->container instanceof Space) {
            $reasons[ReportContent::REASON_NOT_BELONG] = Yii::t('ReportcontentModule.base', 'Wrong Space');
        }

        $reasons[ReportContent::REASON_INCORRECT] = Yii::t('ReportcontentModule.base', 'Misleading');
        $reasons[ReportContent::REASON_OFFENSIVE] = Yii::t('ReportcontentModule.base', 'Offensive');
        $reasons[ReportContent::REASON_SPAM] = Yii::t('ReportcontentModule.base', 'Spam');

        if ($selectable) {
            return $reasons;
        }

        $reasons[ReportContent::REASON_FILTER] = Yii::t('ReportcontentModule.base', 'Profanity Filter');

        return $reasons;
    }

    public function canDelete(?User $user = null)
    {
        if ($user === null) {
            return false;
        }

        if (Permission::canManageReports($this->content->container, $user)) {
            return true;
        }

        if (empty($this->system_admin_only)) {
            if ($this->content->container->getPermissionManager($user)->can(new ManageContent())) {
                return true;
            }
        }

        return false;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getContent()
    {
        return $this->hasOne(Content::class, ['id' => 'content_id']);
    }

    public function getComment()
    {
        return $this->hasOne(Comment::class, ['id' => 'comment_id']);
    }
}
