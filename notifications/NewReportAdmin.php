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
use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

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

    /**
     * @var Comment|ContentActiveRecord|null Cached record
     */
    private $_reportedRecord = null;

    /**
     * @inheritdoc
     */
    public function html()
    {
        $reportedRecord = $this->getReportedRecord();

        return Yii::t('ReportcontentModule.base', '%displayName% has reported %contentTitle%.', [
            '%displayName%' => $this->originator
                ? '<strong>' . Html::encode($this->originator->displayName) . '</strong>'
                : Yii::t('ReportcontentModule.base', 'System'),
            '%contentTitle%' => $this->getContentInfo($reportedRecord)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        $reportedRecord = $this->getReportedRecord();

        if (Yii::$app->user->isGuest) {
            return $reportedRecord->getUrl();
        }

        if (Yii::$app->user->isAdmin()) {
            return Url::to(['/reportcontent/admin']);
        }

        if ($reportedRecord->content->container instanceof Space &&
            $reportedRecord->content->container->isAdmin(Yii::$app->user->id)) {
            return $reportedRecord->content->container->createUrl('/reportcontent/space-admin');
        }

        return $reportedRecord->getUrl();
    }

    /**
     * @return Comment|ContentActiveRecord|null
     */
    private function getReportedRecord()
    {
        if ($this->_reportedRecord) {
            return $this->_reportedRecord;
        }

        /* @var ReportContent $report */
        $report = $this->source;

        if (empty($report->comment_id)) {
            $this->_reportedRecord = Content::findOne(['id' => $report->content_id])->getModel();
        } else {
            $this->_reportedRecord = Comment::findOne(['id' => $report->comment_id]);
        }

        return $this->_reportedRecord;
    }
}