<?php

/**
 * ReportContentModule is responsible for allowing useres to report posts.
 *
 * @author Marjana Pesic
 *
 */
class ReportContentModule extends HWebModule
{
    
    // http://www.yiiframework.com/wiki/148/understanding-assets/
    // getAssetsUrl()
    // return the URL for this module's assets, performing the publish operation
    // the first time, and caching the result for subsequent use.
    private $_assetsUrl;

    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('reportcontent.assets'));
        return $this->_assetsUrl;
    }

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
    }

    public static function onWallEntryControlsInit($event)
    {
        $event->sender->addWidget('application.modules.reportcontent.widgets.ReportContentWidget', array(
            'content' => $event->sender->object
        ));
    }
    
    /**
     * On content deletion make sure to delete all its reports
     *
     * @param CEvent $event
     */
    public static function onContentDelete($event)
    {
    
        foreach (ReportContent::model()->findAllByAttributes(array('object_model' => get_class($event->sender), 'object_id' => $event->sender->id)) as $report) {
            $report->delete();
        }
    }
    
    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param type $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('ReportContentModule.base', 'Reported posts'),
            'url' => Yii::app()->createUrl('//reportcontent/admin'),
            'group' => 'manage',
            'icon' => '<i class="fa fa-exclamation-triangle"></i>',
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'reportcontent' && Yii::app()->controller->id == 'admin'),
            'sortOrder' => 510,
        ));
    }
    
    public static function onSpaceAdminMenuInit($event)
    {
        $space = Yii::app()->getController()->getSpace();
        if($space->isAdmin())
            $event->sender->addItem(array(
                'label' => Yii::t('ReportContentModule.base', 'Reported posts'),
                'url' => Yii::app()->createUrl('//reportcontent/spaceAdmin', array('sguid'=>$space->guid)),
                'group' => 'admin',
                'icon' => '<i class="fa fa-exclamation-triangle"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'reportcontent' && Yii::app()->controller->id == 'spaceAdmin'),
                'sortOrder' => 510,
            ));
    }
    
    protected function beforeDelete()
    {
    
        Notification::remove('ReportContent', $this->id);
    
        return parent::beforeDelete();
    }
    
    public function disable()
    {
        if (parent::disable()) {
            foreach (Notification::model()->findAllByAttributes(array('source_object_model' => 'ReportContent')) as $notification) {
                $notification->delete();
            }
            ReportContent::model()->deleteAll();
            return true;
        }
        return false;
    }
}
?>