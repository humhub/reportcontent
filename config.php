<?php

use humhub\commands\IntegrityController;

return [
    'id' => 'reportcontent',
    'class' => 'humhub\modules\reportcontent\Module',
    'namespace' => 'humhub\modules\reportcontent',
    'events' => [
        ['class' => humhub\modules\content\widgets\WallEntryControls::className(), 'event' => humhub\modules\content\widgets\WallEntryControls::EVENT_INIT, 'callback' => ['humhub\modules\reportcontent\Events', 'onWallEntryControlsInit']],
        ['class' => humhub\modules\content\components\ContentActiveRecord::className(), 'event' => \humhub\modules\content\components\ContentActiveRecord::EVENT_BEFORE_DELETE, 'callback' => ['humhub\modules\reportcontent\Events', 'onContentDelete']],
        ['class' => humhub\modules\admin\widgets\AdminMenu::className(), 'event' => humhub\modules\admin\widgets\AdminMenu::EVENT_INIT, 'callback' => ['humhub\modules\reportcontent\Events', 'onAdminMenuInit']],
        ['class' => humhub\modules\space\widgets\AdminMenu::className(), 'event' => humhub\modules\space\widgets\AdminMenu::EVENT_INIT, 'callback' => ['humhub\modules\reportcontent\Events', 'onSpaceAdminMenuInit']],
        ['class' => IntegrityController::className(), 'event' => IntegrityController::EVENT_ON_RUN, 'callback' => array('humhub\modules\reportcontent\Events', 'onIntegrityCheck')],
    ],
];
?>