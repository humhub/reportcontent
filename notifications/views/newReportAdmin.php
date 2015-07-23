<?php

use yii\helpers\Html;

switch ($source->reason) {
    case 1:
        echo Yii::t('ReportcontentModule.views_notifications_newReportAdmin', "%displayName% has reported %contentTitle% for not belonging to the space.", array(
            '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
            '%contentTitle%' => $this->context->getContentInfo($source->content->getPolymorphicRelation())
        ));
        break;
    case 2:
        echo Yii::t('ReportcontentModule.views_notifications_newReportAdmin', "%displayName% has reported %contentTitle% as offensive.", array(
            '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
            '%contentTitle%' => $this->context->getContentInfo($source->content->getPolymorphicRelation())
        ));
        break;
    case 3:
        echo Yii::t('ReportcontentModule.views_notifications_newReportAdmin', "%displayName% has reported %contentTitle% as spam.", array(
            '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
            '%contentTitle%' => $this->context->getContentInfo($source->content->getPolymorphicRelation())
        ));
        break;
}
?>
