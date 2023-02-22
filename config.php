<?php

use humhub\commands\IntegrityController;

return [
    'id' => 'reportcontent',
    'class' => 'humhub\modules\reportcontent\Module',
    'namespace' => 'humhub\modules\reportcontent',
    'events' => [
        [humhub\modules\content\widgets\WallEntryControls::class, humhub\modules\content\widgets\WallEntryControls::EVENT_INIT,
            ['humhub\modules\reportcontent\Events', 'onWallEntryControlsInit']],
        [humhub\modules\admin\widgets\AdminMenu::class, humhub\modules\admin\widgets\AdminMenu::EVENT_INIT,
            ['humhub\modules\reportcontent\Events', 'onAdminMenuInit']],
        [humhub\modules\space\widgets\HeaderControlsMenu::class, humhub\modules\space\widgets\HeaderControlsMenu::EVENT_INIT,
            ['humhub\modules\reportcontent\Events', 'onSpaceAdminMenuInit']],
        [IntegrityController::class, IntegrityController::EVENT_ON_RUN,
            ['humhub\modules\reportcontent\Events', 'onIntegrityCheck']],
        [\humhub\modules\comment\widgets\CommentControls::class, \humhub\modules\ui\menu\widgets\Menu::EVENT_INIT,
            ['humhub\modules\reportcontent\Events', 'onCommentControlsInit']],
    ]

]
?>