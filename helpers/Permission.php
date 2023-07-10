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
use humhub\modules\reportcontent\services\PermissionService;
use humhub\modules\user\models\User;

class Permission
{

    public static function canReportContent(ContentActiveRecord $record, ?User $user = null): bool
    {
        $service = new PermissionService($record->content->container, $user);
        return $service->canReportAuthorContent($record->content->created_by);
    }

    public static function canReportComment(Comment $comment, ?User $user = null): bool
    {
        $service = new PermissionService($comment->content->container, $user);
        return $service->canReportAuthorContent($comment->created_by);
    }

    public static function canManageReports(?ContentContainerActiveRecord $container, ?User $user = null): bool
    {
        $service = new PermissionService($container, $user);
        return $service->canManageSpaceReports() || $service->canManageUserReports();
    }
}
