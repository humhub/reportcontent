<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\reportcontent\notifications;

use humhub\modules\notification\components\BaseNotification;

/**
 * Notifies an admin about reported content
 *
 * @since 0.5
 */
class NewReportAdmin extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = 'reportcontent';

    /**
     * @inheritdoc
     */
    public $viewName = "newReportAdmin";

}

?>
