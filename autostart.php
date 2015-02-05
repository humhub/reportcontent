<?php

Yii::app()->moduleManager->register(array(
    'id' => 'reportcontent',
    'class' => 'application.modules.reportcontent.ReportContentModule',
    'import' => array(
        'application.modules.reportcontent.*',
		'application.modules.reportcontent.models.*',
		'application.modules.reportcontent.forms.*',
		'application.modules.reportcontent.notifications.*',
    ),
    // Events to Catch 
    'events' => array(
		array('class' => 'WallEntryControlsWidget', 'event' => 'onInit', 'callback' => array('ReportContentModule', 'onWallEntryControlsInit')),
		array('class' => 'HActiveRecordContent', 'event' => 'onBeforeDelete', 'callback' => array('ReportContentModule', 'onContentDelete')),
    ),
));
?>