<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\reportcontent\notifications;

use humhub\modules\comment\models\Comment;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\interfaces\ContentOwner;
use humhub\modules\content\models\Content;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\notification\components\NotificationCategory;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\Url;

/**
 * Notifies an admin about reported content
 *
 * @since 0.5
 */
final class NewReportAdmin extends BaseNotification
{
    /**
     * @var Comment|ContentActiveRecord|null Cached record
     */
    private $_reportedRecord = null;

    /**
     * @inheritdoc
     */
    public static function category(): NotificationCategory
    {
        return NotificationCategory::admin();
    }

    /**
     * @inheritdoc
     */
    protected function getMessage(array $params): string
    {
        return Yii::t('ReportcontentModule.base', '{displayName} has reported {content}.', $params);
    }

    /**
     * @inheritdoc
     */
    protected function getMessageParams(): array
    {
        // A report without a reporter was created by the profanity filter
        return $this->originator === null
            ? ['displayName' => Yii::t('ReportcontentModule.base', 'System')]
            : [];
    }

    /**
     * The sentence names the reported content or comment, not the content of the report.
     *
     * @inheritdoc
     */
    public function getSubjectRecord(): ?ContentOwner
    {
        $record = $this->getReportedRecord();

        return $record instanceof ContentOwner ? $record : null;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(bool $scheme = false): ?string
    {
        $reportedRecord = $this->getReportedRecord();

        if ($reportedRecord === null) {
            return Url::to([$this->recipient->isSystemAdmin() ? '/reportcontent/admin' : '/dashboard'], $scheme);
        }

        if ($this->recipient->isSystemAdmin()) {
            return Url::to(['/reportcontent/admin'], $scheme);
        }

        $container = $reportedRecord->content->container;
        if ($container instanceof Space && $container->isAdmin($this->recipient->id)) {
            return $container->createUrl('/reportcontent/space-admin', [], $scheme);
        }

        return $reportedRecord instanceof Comment
            ? $reportedRecord->getUrl($scheme)
            : $reportedRecord->content->getUrl($scheme);
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
        $report = $this->sourceRecord;

        if (!$report instanceof ReportContent) {
            return null;
        }

        if (empty($report->comment_id)) {
            $this->_reportedRecord = Content::findOne(['id' => $report->content_id])?->getModel();
        } else {
            $this->_reportedRecord = Comment::findOne(['id' => $report->comment_id]);
        }

        return $this->_reportedRecord;
    }
}
