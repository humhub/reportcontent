<?php

namespace humhub\modules\reportcontent\models;

use humhub\components\behaviors\PolymorphicRelation;
use humhub\modules\content\components\ContentAddonActiveRecord;
use humhub\modules\content\permissions\ManageContent;
use humhub\modules\reportcontent\notifications\NewReportAdmin;
use humhub\modules\user\models\Group;
use Yii;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\models\Content;

/**
 * This is the model class for table "report_content".
 *
 * The followings are the available columns in table 'report_content':
 * @property integer $id
 * @property string $object_model
 * @property integer $object_id
 * @property integer $reason
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property boolean $system_admin_only
 *
 * @package humhub.modules.reportcontent.models
 */
class ReportContent extends ContentAddonActiveRecord
{

    const REASON_NOT_BELONG = 1;
    const REASON_OFFENSIVE = 2;
    const REASON_SPAM = 3;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => PolymorphicRelation::className(),
                'mustBeInstanceOf' => [ContentActiveRecord::className()],
            ]
        ];
    }

    /**
     *
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'report_content';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['object_id', 'reason'], 'required'],
            [['object_id', 'created_by', 'updated_by'], 'integer',],
            ['created_at', 'string', 'max' => 45],
            [['updated_at'], 'safe']
        ];
    }

    /**
     * Sends a notification to eihter space admins or system admins after the creation of a report.
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if ($this->content->contentContainer instanceof Space && !$this->content->contentContainer->isAdmin($this->content->created_by)) {
                $query = User::find()
                    ->leftJoin('space_membership', 'space_membership.user_id=user.id AND space_membership.space_id=:spaceId AND space_membership.group_id=:groupId', [':spaceId' => $this->content->contentContainer->id, ':groupId' => 'admin'])
                    ->where(['IS NOT', 'space_membership.space_id', new \yii\db\Expression('NULL')]);
            } else {
                $query = Group::getAdminGroup()->getUsers();
            }

            $notification = new NewReportAdmin;
            $notification->source = $this;
            $notification->originator = Yii::$app->user->getIdentity();
            $notification->sendBulk($query);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public static function getReason($reason)
    {
        switch ($reason) {
            case ReportContent::REASON_NOT_BELONG:
                return Yii::t('ReportcontentModule.models_ReportContent', "Doesn't belong to space");
            case ReportContent::REASON_OFFENSIVE:
                return Yii::t('ReportcontentModule.models_ReportContent', "Offensive");
            case ReportContent::REASON_SPAM:
                return Yii::t('ReportcontentModule.models_ReportContent', "Spam");
        }
    }

    public static function canReportPost(ContentActiveRecord $post, $userId = null)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $user = ($userId != null) ? User::findOne(['id' => $userId]) : Yii::$app->user->getIdentity();

        if ($user == null || $user->isSystemAdmin()) {
            return false;
        }

        // Can't report own content
        if ($post->content->created_by == $user->id) {
            return false;
        }

        // Space admins can't report since they can simply delete content
        if ($post->content->container instanceof Space && $post->content->getContainer()->isAdmin($user->id)) {
            return false;
        }

        // Check if post exists
        if (ReportContent::findOne(['object_model' => $post->className(), 'object_id' => $post->id, 'created_by' => $user->id]) !== null) {
            return false;
        }

        // Don't report system admin content
        if (User::findOne(['id' => $post->content->created_by])->isSystemAdmin()) {
            return false;
        }

        return true;
    }

    public function canDelete($userId = null)
    {

        if (Yii::$app->user->isGuest) {
            return false;
        }


        $user = ($userId == null) ? Yii::$app->user->getIdentity() : User::findOne(['id' => $userId]);

        if ($user->isSystemAdmin()) {
            return true;
        }

        if ($this->content->getContainer()->permissionManager->can(new ManageContent())) {
            return true;
        }

        if ($this->getSource()->content->container instanceof Space && $this->getSource()->content->container->isAdmin($user->id)) {
            return true;
        }

        return false;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getContent()
    {
        return $this->hasOne(Content::className(), ['object_id' => 'object_id', 'object_model' => 'object_model']);
    }

}

?>
