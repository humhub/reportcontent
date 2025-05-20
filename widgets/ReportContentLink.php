<?php

namespace humhub\modules\reportcontent\widgets;

use humhub\components\Widget;
use humhub\helpers\Html;
use humhub\modules\reportcontent\helpers\Permission;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\ui\icon\widgets\Icon;
use Yii;
use yii\helpers\Url;

class ReportContentLink extends Widget
{
    /**
     * @var ContentActiveRecord
     */
    public $record;

    /**
     * Executes the widget.
     */
    public function run()
    {
        if (Permission::canReportContent($this->record)) {
            $reportUrl = Url::to(['/reportcontent/report', 'contentId' => $this->record->content->id]);

            return Html::tag(
                'li',
                Html::a(
                    Icon::get('exclamation-triangle'). ' ' . Yii::t('ReportcontentModule.base', 'Report'),
                    '#',
                    ['data-action-click' => 'ui.modal.load', 'data-action-click-url' => $reportUrl],
                ),
            );
        }
    }

}
