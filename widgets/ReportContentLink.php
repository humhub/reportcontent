<?php

namespace humhub\modules\reportcontent\widgets;

use humhub\components\Widget;
use humhub\helpers\Html;
use humhub\modules\reportcontent\helpers\Permission;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\widgets\bootstrap\Link;
use Yii;

class ReportContentLink extends Widget
{
    /**
     * @var ContentActiveRecord
     */
    public $record;

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return parent::beforeRun() && Permission::canReportContent($this->record);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return Html::tag(
            'li',
            Link::modal(Yii::t('ReportcontentModule.base', 'Report'))
                ->icon('exclamation-triangle')
                ->load(['/reportcontent/report', 'contentId' => $this->record->content->id])
                ->cssClass('dropdown-item '),
        );
    }

}
