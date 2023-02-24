<?php

use humhub\commands\IntegrityController;
use humhub\components\ActiveRecord;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\Events;
use humhub\modules\comment\models\Comment;

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

        [Post::class, ActiveRecord::EVENT_APPEND_RULES, [Events::class, 'onPostAppendRules']],
        [Post::class, ActiveRecord::EVENT_AFTER_INSERT, [Events::class, 'onPostAfterSave']],
        [Comment::class, ActiveRecord::EVENT_APPEND_RULES, [Events::class, 'onCommentAppendRules']],
        [Comment::class, ActiveRecord::EVENT_AFTER_INSERT, [Events::class, 'onCommentAfterSave']],
    ]

]
?>