<?php

namespace humhub\modules\reportcontent;

use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\space\models\Space;
use yii\helpers\Url;
use Yii;

class Events
{
    public static function onWallEntryControlsInit($event)
    {
        $event->sender->addWidget(widgets\ReportContentWidget::class, [
            'post' => $event->sender->object
        ]);
    }

    public static function onContentDelete($event)
    {
        foreach (ReportContent::findAll(['object_model' => get_class($event->sender), 'object_id' => $event->sender->id]) as $report) {
            $report->delete();
        }
    }

    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('ReportcontentModule.base', 'Reported posts'),
            'url' => Url::to(['/reportcontent/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-exclamation-triangle"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'reportcontent' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 510,
        ));
    }

    public static function onSpaceAdminMenuInit($event)
    {
        /** @var Space $space */
        $space = $event->sender->space;

        if ($space->isAdmin(Yii::$app->user->id)) {
            $event->sender->addItem([
                'label' => Yii::t('ReportcontentModule.base', 'Reported posts'),
                'url' => $space->createUrl('/reportcontent/space-admin'),
                'group' => 'admin',
                'icon' => '<i class="fa fa-exclamation-triangle"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'reportcontent' && Yii::$app->controller->id == 'space-admin'),
                'sortOrder' => 510,
            ]);
        }
    }

    public static function onIntegrityCheck($event)
    {
        $integrityController = $event->sender;
        $integrityController->showTestHeadline("ReportContent Module (" . ReportContent::find()->count() . " entries)");
        foreach (ReportContent::find()->joinWith('content')->all() as $rc) {
            if ($rc->content === null) {
                if ($integrityController->showFix("Deleting report id " . $rc->id . " without existing content!")) {
                    $rc->delete();
                }
            }
        }

    }

}
