<?php

namespace humhub\modules\reportcontent\widgets;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\reportcontent\helpers\Permission;
use humhub\widgets\menu\MenuLink;
use Yii;
use yii\helpers\Url;

/**
 * The "Report" entry of a content's context menu ({@see \humhub\modules\content\widgets\WallEntryControls}).
 *
 * A menu entry, not a widget, since 1.20: the context menu describes its entries to the client.
 */
class ReportContentLink extends MenuLink
{
    /**
     * @var ContentActiveRecord
     */
    public $record;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!Permission::canReportContent($this->record)) {
            $this->setIsVisible(false);
            return;
        }

        $this->setLabel(Yii::t('ReportcontentModule.base', 'Report'));
        $this->setIcon('alert-triangle');
        $this->setUrl('#');
        $this->setHtmlOptions([
            'data-action-click' => 'ui.modal.load',
            'data-action-url' => Url::to(['/reportcontent/report', 'contentId' => $this->record->content->id]),
        ]);
    }
}
