<?php

namespace humhub\modules\reportcontent;

use humhub\modules\comment\models\Comment;
use humhub\modules\comment\widgets\CommentControls;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\helpers\Permission;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\space\models\Space;
use humhub\modules\ui\menu\MenuLink;
use yii\db\AfterSaveEvent;
use yii\db\Expression;
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

        if (!Permission::canReportComment($menu->comment)) {
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
            'label' => Yii::t('ReportcontentModule.base', 'Reported Content') . self::getReportsCountBadge(),
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

        if (Permission::canManageReports($space)) {
            $event->sender->addItem([
                'label' => Yii::t('ReportcontentModule.base', 'Reported Content') . self::getReportsCountBadge($space),
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
                            'Your contribution does not comply with our community guidelines and can therefore not be published. For further details, please contact the administrators.'
                        ));
                    }
                }
            }, 'skipOnEmpty' => false],
        ];
    }

    public static function onPostAfterSave(AfterSaveEvent $event)
    {
        /** @var Post $post */
        $post = $event->sender;

        if (!array_key_exists('message', $event->changedAttributes)) {
            return;
        }

        if (!self::blockFilteredPosts() && self::matchProfanityFilter($post->message)) {
            $report = ReportContent::find()
                ->where(['content_id' => $post->content->id])
                ->andWhere(['IS', 'comment_id', new Expression('NULL')])
                ->andWhere(['reason' => ReportContent::REASON_FILTER]);
            if ($report->exists()) {
                return;
            }
            $report = new ReportContent();
            $report->reason = ReportContent::REASON_FILTER;
            $report->content_id = $post->content->id;
            if (!$report->save(false)) {
                Yii::error('Could not save report for Post! ' . print_r($report->getErrors(), 1), 'reportcontent');
            }
        }
    }

    public static function onCommentAfterSave(AfterSaveEvent $event)
    {
        /** @var Comment $comment */
        $comment = $event->sender;

        if (!array_key_exists('message', $event->changedAttributes)) {
            return;
        }

        if (!self::blockFilteredPosts() && self::matchProfanityFilter($comment->message)) {
            $report = ReportContent::find()
                ->where(['content_id' => $comment->content->id])
                ->andWhere(['comment_id' => $comment->id])
                ->andWhere(['reason' => ReportContent::REASON_FILTER]);
            if ($report->exists()) {
                return;
            }
            $report = new ReportContent();
            $report->reason = ReportContent::REASON_FILTER;
            $report->content_id = $comment->content->id;
            $report->comment_id = $comment->id;
            if (!$report->save(false)) {
                Yii::error('Could not save report for Comment! ' . print_r($report->getErrors(), 1), 'reportcontent');
            }
        }
    }

    public static function onCommentAppendRules($event)
    {
        $event->result = [
            [['message'], function ($attribute) {
                /* @var Comment $this */
                if (self::matchProfanityFilter($this->message)) {
                    if (self::blockFilteredPosts()) {
                        $this->addError($attribute, Yii::t('ReportcontentModule.base',
                            'Your contribution does not comply with our community guidelines and can therefore not be published. For further details, please contact the administrators.'
                        ));
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
            if (preg_match("/\b$word\b/ui", $text)) {
                return true;
            }
        }

        return false;
    }

    private static function getReportsCountBadge(?ContentContainerActiveRecord $container = null): string
    {
        $reportsCount = ReportContent::find()->readable($container)->count();

        return $reportsCount > 0
            ? '&nbsp;&nbsp;<span class="label label-danger">' . $reportsCount . '</span>'
            : '';
    }
}
