<?php

Yii::app()->moduleManager->register(array(
    'id' => 'reportcontent',
    'class' => 'application.modules.reportcontent.ReportContentModule',
    'import' => array(
        'application.modules.reportcontent.*',
		'application.modules.reportcontent.models.*',
		'application.modules.reportcontent.forms.*',
		'application.modules.reportcontent.notifications.*',
		'application.modules.reportcontent.widgets.*',
    ),
    // Events to Catch 
    'events' => array(
        array('class' => 'SpaceAdminMenuWidget', 'event' => 'onInit', 'callback' => array('ReportContentModule', 'onSpaceAdminMenuInit')),
		array('class' => 'WallEntryControlsWidget', 'event' => 'onInit', 'callback' => array('ReportContentModule', 'onWallEntryControlsInit')),
		array('class' => 'HActiveRecordContent', 'event' => 'onBeforeDelete', 'callback' => array('ReportContentModule', 'onContentDelete')),
		array('class' => 'AdminMenuWidget', 'event' => 'onInit', 'callback' => array('ReportContentModule', 'onAdminMenuInit')),

    ),
));
?>