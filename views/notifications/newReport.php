<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification)); ?>

<?php switch ($sourceObject->getReason()){
	case 1: 
		echo Yii::t('ReportContentModule.views_notifications_newReport', "An user has reported your post for not belonging to the space.");
		break;
	case 2:
		echo Yii::t('ReportContentModule.views_notifications_newReport', "An user has reported your post as offensive.");
		break;
	case 3:
		echo Yii::t('ReportContentModule.views_notifications_newReport', "An user has reported your post as spam.");
		break;
}?>

<?php $this->endContent(); ?>