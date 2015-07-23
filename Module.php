<?php

namespace humhub\modules\reportcontent;


/**
 * ReportContentModule is responsible for allowing useres to report posts.
 *
 * @author Marjana Pesic
 *
 */
class Module extends \humhub\components\Module
{

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