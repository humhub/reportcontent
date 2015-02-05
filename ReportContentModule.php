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