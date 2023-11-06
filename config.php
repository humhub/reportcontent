<?php

use humhub\commands\IntegrityController;
use humhub\components\ActiveRecord;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\comment\models\Comment;
use humhub\modules\comment\widgets\CommentControls;
use humhub\modules\content\models\Content;
use humhub\modules\content\widgets\WallEntryControls;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\Events;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\modules\ui\menu\widgets\Menu;

return [
    'id' => 'reportcontent',
    'class' => 'humhub\modules\reportcontent\Module',
    'namespace' => 'humhub\modules\reportcontent',
    'events' => [
        [WallEntryControls::class, WallEntryControls::EVENT_INIT, [Events::class, 'onWallEntryControlsInit']],
        [AdminMenu::class, AdminMenu::EVENT_INIT, [Events::class, 'onAdminMenuInit']],
        [HeaderControlsMenu::class, HeaderControlsMenu::EVENT_INIT, [Events::class, 'onSpaceAdminMenuInit']],
        [IntegrityController::class, IntegrityController::EVENT_ON_RUN, [Events::class, 'onIntegrityCheck']],
        [CommentControls::class, Menu::EVENT_INIT, [Events::class, 'onCommentControlsInit']],
        [Post::class, ActiveRecord::EVENT_APPEND_RULES, [Events::class, 'onPostAppendRules']],
        [Post::class, ActiveRecord::EVENT_AFTER_INSERT, [Events::class, 'onPostAfterSave']],
        [Post::class, ActiveRecord::EVENT_AFTER_UPDATE, [Events::class, 'onPostAfterSave']],
        [Comment::class, ActiveRecord::EVENT_APPEND_RULES, [Events::class, 'onCommentAppendRules']],
        [Comment::class, ActiveRecord::EVENT_AFTER_INSERT, [Events::class, 'onCommentAfterSave']],
        [Comment::class, ActiveRecord::EVENT_AFTER_UPDATE, [Events::class, 'onCommentAfterSave']],
        [Content::class, Content::EVENT_BEFORE_DELETE, [Events::class, 'onContentBeforeDelete']],
    ]
];
