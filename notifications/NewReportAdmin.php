<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\reportcontent\notifications;

use humhub\modules\comment\models\Comment;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\reportcontent\models\ReportContent;
use Yii;
use yii\helpers\Html;

/**
 * Notifies an admin about reported content
 *
 * @since 0.5
 */
class NewReportAdmin extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $requireOriginator = false;

    /**
     * @inheritdoc
     */
    public $moduleId = 'reportcontent';

    public function html()
    {

        /** @var ReportContent $report */
        $report = $this->source;

        /** @var ContentActiveRecord|Comment $reportedRecord */
        $reportedRecord = null;
        if (!empty($report->comment_id)) {
            $reportedRecord = Comment::findOne(['id' => $report->comment_id]);
        } else {
            $reportedRecord = Content::findOne(['id' => $report->content_id])->getModel();
        }

        switch ($this->source->reason) {
            case ReportContent::REASON_NOT_BELONG:
                return Yii::t('ReportcontentModule.base', "%displayName% has reported %contentTitle% for not belonging to the space.", [
                    '%displayName%' => ($this->originator) ? '<strong>' . Html::encode($this->originator->displayName) . '</strong>' : 'System',
                    '%contentTitle%' => $this->getContentInfo($reportedRecord)
                ]);
                break;
            case ReportContent::REASON_OFFENSIVE:
                return Yii::t('ReportcontentModule.base', "%displayName% has reported %contentTitle% as offensive.", [
                    '%displayName%' => ($this->originator) ? '<strong>' . Html::encode($this->originator->displayName) . '</strong>' : 'System',
                    '%contentTitle%' => $this->getContentInfo($reportedRecord)
                ]);
                break;
            case ReportContent::REASON_SPAM:
                return Yii::t('ReportcontentModule.base', "%displayName% has reported %contentTitle% as spam.", [
                    '%displayName%' => ($this->originator) ? '<strong>' . Html::encode($this->originator->displayName) . '</strong>' : 'System',
                    '%contentTitle%' => $this->getContentInfo($reportedRecord)
                ]);
                break;
            case ReportContent::REASON_FILTER:
                return Yii::t('ReportcontentModule.base', "%displayName% has reported %contentTitle% as profanity.", [
                    '%displayName%' => ($this->originator) ? '<strong>' . Html::encode($this->originator->displayName) . '</strong>' : 'System',
                    '%contentTitle%' => $this->getContentInfo($reportedRecord)
                ]);
                break;
        }
    }
}

?>
