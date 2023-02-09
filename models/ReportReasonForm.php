<?php

namespace humhub\modules\reportcontent\models;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\space\models\Space;

use Yii;
use yii\base\Model;

class ReportReasonForm extends Model
{
    public $content_id;
    public $reason;
    private $_postModel;


    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['content_id'], 'required'],
            [['reason'], 'safe'],
            // Workaround for not displaying required * on radio labels.
            [['content_id'], 'requiredReason']
        ];
    }

    /**
     * @inheritDoc
     */
    public function requiredReason($attribute, $model)
    {
        if (empty($this->reason)) {
            $this->addError('reason',
                Yii::t('ReportcontentModule.base', 'Please provide a reason, why you want to report this content.'));
        }
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'reason' => Yii::t('ReportcontentModule.base', 'Why do you want to report this post?')
        ];
    }

    /**
     * Validates the the formdata and creates a new ReportContent model if validation succeeded.
     *
     * @return boolean if validation and persisting of the report model succeeded
     */
    public function save()
    {
        $model = $this->getPostModel();
        if (!$this->validate() || !ReportContent::canReportPost($model)) {
            return false;
        }

        $report = new ReportContent();
        $report->reason = $this->reason;
        $report->object_model = get_class($model);
        $report->object_id = $model->getPrimaryKey();

        $contentContainer = $model->content->getContainer();

        // If we report a space admin post, we create a system admin only report (only visible in admin area)
        if (!($contentContainer instanceof Space) || $contentContainer->isAdmin($model->content->created_by)) {
            $report->system_admin_only = true;
        }

        return $report->save();
    }

    /**
     * Returns the associated post model instance.
     * @return ContentActiveRecord|null
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
     * @return array
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