<?php

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
class ReportContent extends HActiveRecordContentAddon
{

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className
     *            active record class name.
     * @return ReportContent the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
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
                'object_id, reason, created_by',
                'required'
            ),
            array(
                'object_id, created_by, updated_by',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'created_at',
                'length',
                'max' => 45
            ),
            array(
                'updated_at',
                'safe'
            )
        );
    }

    protected function afterSave()
    {
        // Send Notifications
        NewReportNotification::fire($this);
        NewReportAdminNotification::fire($this);
    }

    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Checks if the given or current user can report post with given id.
     *
     * @param
     *            int postId
     */
    public static function canReportPost($postId, $userId = "")
    {
        $post = Post::model()->findByPk($postId);
        if (! $post)
            return false;
        
        if ($userId == "")
            $user = Yii::app()->user;
        else
            $user = User::model()->findByPk($userId);
        
        if ($user->isAdmin())
            return false;
        
        if ($post->created_by == $user->id)
            return false;
        
        if ($post->content->getContainer() instanceof Space && ($post->content->getContainer()->isAdmin($user->id) || $post->content->getContainer()->isAdmin($post->created_by)))
            return false;
        
        if (ReportContent::model()->exists('object_model = "Post" and object_id = ' . $post->id . ' and created_by = ' . $user->id))
            return false;
        
        if (User::model()->exists('id = ' . $post->created_by . ' and super_admin = 1'))
            return false;
        
        return true;
    }
}
?>