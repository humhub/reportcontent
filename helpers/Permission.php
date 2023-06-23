<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\reportcontent\helpers;

use humhub\modules\comment\models\Comment;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;

class Permission
{

    public static function isReportableContainer(?ContentContainerActiveRecord $container): bool
    {
        // Only Space contents support a reporting
        return $container instanceof Space;
    }

    public static function isAnotherUser($authorId, ?User $user = null): bool
    {
        if ($user === null && !Yii::$app->user->isGuest) {
            $user = Yii::$app->user->getIdentity();
        }

        return $user instanceof User && $authorId != $user->id;
    }

    public static function canReportContent(ContentActiveRecord $record, ?User $user = null): bool
    {
        return self::isReportableContainer($record->content->container) &&
            self::isAnotherUser($record->content->created_by, $user);
    }

    public static function canReportComment(Comment $comment, ?User $user = null): bool
    {
        return self::isReportableContainer($comment->content->container) &&
            self::isAnotherUser($comment->created_by, $user);
    }

    public static function canManageReports(?ContentContainerActiveRecord $container, ?User $user = null): bool
    {
        if (!self::isReportableContainer($container)) {
            return false;
        }

        /* @var Space $container */
        if ($container->isAdmin($user)) {
            return true;
        }

        // Moderators can manage the reports
        $membership = $container->getMembership($user);
        return $membership && $membership->isPrivileged();
    }
}
