<?php

namespace humhub\modules\reportcontent\models;

use Yii;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\post\models\Post;
use humhub\modules\content\models\Content;

/**
 * This is the model class for table "report_content".
 *
 * The followings are the available columns in table 'report_content':
 * @property integer $id
 * @property integer $post_id
 * @property integer $reason
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @package humhub.modules.reportcontent.models
 */
class ReportContent extends \humhub\modules\content\components\ContentAddonActiveRecord
{

    const REASON_NOT_BELONG = 1;
    const REASON_OFFENSIVE = 2;
    const REASON_SPAM = 3;

    /**
     *
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'report_content';
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                ['object_id', 'reason', 'created_by'],
                'required'
            ),
            array(
                ['object_id', 'created_by', 'updated_by'],
                'integer',
            ),
            array(
                'created_at',
                'string',
                'max' => 45
            ),
            array(
                ['updated_at'],
                'safe'
            )
        );
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {

            if ($this->content->space !== null) {
                $query = User::find()
                        ->leftJoin('space_membership', 'space_membership.user_id=user.id AND space_membership.space_id=:spaceId AND space_membership.admin_role=1', [':spaceId' => $this->content->space->id])
                        ->where(['IS NOT', 'space_membership.space_id', new \yii\db\Expression('NULL')]);
            } else {
                $query = User::find()->where(['super_admin' => 1]);
            }

            $notification = new \humhub\modules\reportcontent\notifications\NewReportAdmin;
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

    /**
     * Checks if the given or current user can report post with given id.
     *
     * @param
     *            int postId
     */
    public static function canReportPost($postId, $userId = "")
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $post = Post::findOne(['id' => $postId]);
        if (!$post)
            return false;

        if ($userId != "") {
            $user = User::findOne(['id' => $userId]);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        if (!$user)
            return false;

        if ($user->super_admin)
            return false;

        if ($post->created_by == $user->id)
            return false;

        if ($post->content->container instanceof Space && ($post->content->getContainer()->isAdmin($user->id) || $post->content->getContainer()->isAdmin($post->created_by)))
            return false;

        if (ReportContent::findOne(['object_model' => Post::className(), 'object_id' => $post->id, 'created_by' => $user->id]) !== null)
            return false;

        if (User::findOne(['id' => $post->created_by, 'super_admin' => 1]) !== null)
            return false;

        return true;
    }

    public function canDelete($userId = "")
    {

        if (Yii::$app->user->isGuest) {
            return false;
        }

        if ($userId == "")
            $userId = Yii::$app->user->id;

        if (Yii::$app->user->isAdmin()) {
            return true;
        }

        if ($this->getSource()->content->container instanceof Space && $this->getSource()->content->container->isAdmin($userId)) {
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