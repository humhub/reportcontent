<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\reportcontent;

use humhub\modules\reportcontent\models\ReportContent;
use yii\helpers\Url;
use Yii;

/**
 * Description of Events
 *
 * @author luke
 */
class Events extends \yii\base\Object
{

    public static function onWallEntryControlsInit($event)
    {
        $event->sender->addWidget(widgets\ReportContentWidget::className(), array(
            'content' => $event->sender->object
        ));
    }

    /**
     * On content deletion make sure to delete all its reports
     *
     * @param CEvent $event
     */
    public static function onContentDelete($event)
    {
        foreach (ReportContent::findAll(array('object_model' => get_class($event->sender), 'object_id' => $event->sender->id)) as $report) {
            $report->delete();
        }
    }

    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param type $event
     */
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
        $space = $event->sender->space;
        if ($space->isAdmin())
            $event->sender->addItem(array(
                'label' => Yii::t('ReportcontentModule.base', 'Reported posts'),
                'url' => $space->createUrl('/reportcontent/space-admin'),
                'group' => 'admin',
                'icon' => '<i class="fa fa-exclamation-triangle"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'reportcontent' && Yii::$app->controller->id == 'space-admin'),
                'sortOrder' => 510,
            ));
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
