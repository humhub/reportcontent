<?php $this->beginContent('application.modules_core.notification.views.notificationLayoutMail', array('notification' => $notification, 'showSpace' => true)); ?>

<?php switch ($sourceObject->getReason()){
	case 1: 
		echo Yii::t('ReportContentModule.views_notifications_newReportAdmin', "%displayName% has reported %contentTitle% for not belonging to the space.", array(
		'%displayName%' => '<strong>' . $creator->displayName . '</strong>',
		'%contentTitle%' => NotificationModule::formatOutput($targetObject->getContentTitle())
		));
		break;
	case 2:
		echo Yii::t('ReportContentModule.views_notifications_newReportAdmin', "%displayName% has reported %contentTitle% as offensive.", array(
		'%displayName%' => '<strong>' . $creator->displayName . '</strong>',
		'%contentTitle%' => NotificationModule::formatOutput($targetObject->getContentTitle())
		));
		break;
	case 3:
		echo Yii::t('ReportContentModule.views_notifications_newReportAdmin', "%displayName% has reported %contentTitle% as spam.", array(
		'%displayName%' => '<strong>' . $creator->displayName . '</strong>',
		'%contentTitle%' => NotificationModule::formatOutput($targetObject->getContentTitle())
		));
		break;
}?>

<?php $this->endContent(); ?>