<?php

namespace humhub\modules\reportcontent\models;

use humhub\modules\content\models\Content;
use humhub\modules\space\models\Space;

use Yii;

/**
 * ReportReasonForm
 *
 * @package humhub.modules.reportcontent.forms
 * @author Marjana Pesic
 */
class ReportReasonForm extends \yii\base\Model
{

    public $content_id;
    public $reason;
    private $_postModel;

    /**
     * Initalization of form, removing * from required field
     */
    public function init()
    {
        return parent::init();
    }

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            [['content_id'], 'required'],
            [['reason'], 'safe'],
            // Workaround for not displaying reaquired * on radio labels.
            [['content_id'], 'requiredReason']
        );
    }

    public function requiredReason($attribute, $model)
    {
        if (empty($this->reason)) {
            $this->addError('reason', Yii::t('ReportcontentModule.base', 'Please provide a reason, why you want to report this content.'));
        }
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return [
            'reason' => Yii::t('ReportcontentModule.base', 'Why do you want to report this post?')
        ];
    }

    /**
     * Validates the the formdata and creates a new ReportContent model if validation succeeded.
     * @return boolean if validation and persisting of the report model succeeded
     */
    public function save()
    {
        $post = $this->getPostModel();
        if(!$this->validate() || !ReportContent::canReportPost($post)) {
            return false;
        }
        
        $model = $this->getPostModel();

        $report = new ReportContent();
        $report->reason = $this->reason;
        $report->object_model = $model->className();
        $report->object_id = $model->getPrimaryKey();
        
        $contentContainer = $post->content->getContainer();
        
        // If we report a space admin post, we create a system admin only report (only visible in admin area)
        if(!($contentContainer instanceof Space) || $contentContainer->isAdmin($post->content->created_by)) {
            $report->system_admin_only = true;
        }

        return $report->save();
    }

    /**
     * Returns the associated post model instance.
     * @return type
     */
    public function getPostModel()
    {
        if ($this->_postModel == null) {
            $this->_postModel = Content::findOne(['id' => $this->content_id])->getPolymorphicRelation();
        }

        return $this->_postModel;
    }

    /**
     * Returns a option list for all available reasons.
     * @return type
     */
    public function getReasonOptions()
    {
        return [
            ReportContent::REASON_NOT_BELONG => Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'Does not belong here'),
            ReportContent::REASON_OFFENSIVE => Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'It\'s offensive'),
            ReportContent::REASON_SPAM => Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'It\'s spam')
        ];
    }

}

?>