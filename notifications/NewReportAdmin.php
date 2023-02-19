<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\reportcontent\notifications;

use humhub\modules\notification\components\BaseNotification;
use Yii;
use yii\helpers\Html;

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

    public function html()
    {

        switch ($this->source->reason) {
            case 1:
                return Yii::t('ReportcontentModule.base', "%displayName% has reported %contentTitle% for not belonging to the space.", [
                    '%displayName%' => '<strong>' . Html::encode($this->originator->displayName) . '</strong>',
                    '%contentTitle%' => $this->getContentInfo($this->source->content)
                ]);
                break;
            case 2:
                return Yii::t('ReportcontentModule.base', "%displayName% has reported %contentTitle% as offensive.", [
                    '%displayName%' => '<strong>' . Html::encode($this->originator->displayName) . '</strong>',
                    '%contentTitle%' => $this->getContentInfo($this->source->content)
                ]);
                break;
            case 3:
                return Yii::t('ReportcontentModule.base', "%displayName% has reported %contentTitle% as spam.", [
                    '%displayName%' => '<strong>' . Html::encode($this->originator->displayName) . '</strong>',
                    '%contentTitle%' => $this->getContentInfo($this->source->content)
                ]);
                break;
        }
    }
}

?>
