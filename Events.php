<?php

namespace humhub\modules\reportcontent;

use humhub\modules\comment\models\Comment;
use humhub\modules\comment\widgets\CommentControls;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\space\models\Space;
use humhub\modules\ui\menu\MenuLink;
use yii\helpers\Url;
use Yii;

class Events
{
    public static function onWallEntryControlsInit($event)
    {
        $event->sender->addWidget(widgets\ReportContentLink::class, [
            'record' => $event->sender->object
        ]);
    }


    public static function onCommentControlsInit($event)
    {
        /** @var CommentControls $menu */
        $menu = $event->sender;

        if (!ReportContent::canReportComment($menu->comment, Yii::$app->user->getIdentity())) {
            return;
        }

        $menu->addEntry(new MenuLink([
            'label' => Yii::t('ReportcontentModule.base', 'Report'),
            'icon' => 'fa-exclamation-triangle',
            'url' => '#',
            'htmlOptions' => [
                'data-action-click' => 'ui.modal.load',
                'data-action-click-url' => Url::to([
                    '/reportcontent/report', 'contentId' => $menu->comment->content->id,
                    'commentId' => $menu->comment->id
                ])
            ],
            'sortOrder' => 1000,
        ]));
    }


    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('ReportcontentModule.base', 'Reported Content'),
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
                'label' => Yii::t('ReportcontentModule.base', 'Reported Content'),
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

    public static function onPostAppendRules($event)
    {
        $event->result = [
            [['message'], function ($attribute) {
                /* @var Post $this */
                if (self::matchProfanityFilter($this->message)) {
                    if (self::blockFilteredPosts()) {
                        $this->addError($attribute, Yii::t('ReportcontentModule.base',
                            'Your comment does not comply with the community guidelines and cannot be published. Please contact the administrator for additional information.'
                        ));
                    } else {
                        ;
                    }
                }
            }, 'skipOnEmpty' => false],
        ];
    }

    public static function onCommentAppendRules($event)
    {
        $event->result = [
            [['message'], function ($attribute) {
                /* @var Comment $this */
                if (self::matchProfanityFilter($this->message)) {
                    if (self::blockFilteredPosts()) {
                        $this->addError($attribute, Yii::t('ReportcontentModule.base',
                            'Your comment doe not comply with the community guidelines and cannot be published. Please contact the administrator for additional information.'
                        ));
                    } else {
                        ;
                    }
                }
            }, 'skipOnEmpty' => false],
        ];
    }

    private static function blockFilteredPosts(): bool
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('reportcontent');

        return $module->getConfiguration()->blockContributions;
    }

    private static function matchProfanityFilter($text): bool
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('reportcontent');

        foreach ($module->getConfiguration()->profanityFilter as $word) {
            if (strpos($text, $word) !== false) {
                return true;
            }
        }

        return false;
    }

}