<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\reportcontent\services;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;

class PermissionService
{
    /**
     * @var ContentContainerActiveRecord|Space|User|null
     */
    public $container;

    /**
     * @var User|null
     */
    public $user = null;

    public function __construct(?ContentContainerActiveRecord $container, ?User $user = null)
    {
        $this->container = $container;

        if ($user instanceof User) {
            $this->user = $user;
        } elseif (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->getIdentity();
        }
    }

    public function isReportableContainer(): bool
    {
        return $this->container instanceof Space || $this->container instanceof User;
    }

    public function isAnotherUser($authorId): bool
    {
        return $this->user instanceof User && $authorId != $this->user->id;
    }

    public function canReportAuthorContent($authorId): bool
    {
        return $this->isReportableContainer() && $this->isAnotherUser($authorId);
    }

    public function canManageAllReports(): bool
    {
        return $this->user instanceof User && $this->user->isSystemAdmin();
    }

    public function canManageSpaceReports(): bool
    {
        if (!($this->container instanceof Space)) {
            return false;
        }

        if ($this->canManageAllReports()) {
            return true;
        }

        if ($this->container->isAdmin($this->user)) {
            return true;
        }

        // Space moderators can manage the reports
        $membership = $this->container->getMembership($this->user);
        return $membership && $membership->isPrivileged();
    }

    public function canManageUserReports(): bool
    {
        if (!($this->container instanceof User)) {
            return false;
        }

        return $this->canManageAllReports();
    }
}
