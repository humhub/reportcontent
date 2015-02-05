<?php

/**
 * Notifies an admin that someone has reported post
 *
 * @package humhub.modules.reportcontent.notifications
 */
class NewReportAdminNotification extends Notification
{

    public $webView = "reportcontent.views.notifications.newReportAdmin";

    public $mailView = "application.modules.reportcontent.views.notifications.newReportAdmin_mail";

    /**
     * Fires this notification
     *
     * @param type $report            
     */
    public static function fire($report)
    {
        // if post belongs to the space report to space admin
        if ($report->content->container instanceof Space) {
            
            $admins = SpaceMembership::model()->findAll('admin_role = 1 and space_id = ' . $report->content->space_id);
        }
        
        // if post is profile post report to super admins
        if ($report->content->container instanceof User) {
            $admins = User::model()->findAll('super_admin = 1');
        }
        
        foreach ($admins as $admin) {
            
            $notification = new Notification();
            $notification->class = "NewReportAdminNotification";
            
            if ($report->content->container instanceof Space) {
                $notification->user_id = $admin->user_id;
                $notification->space_id = $report->content->space_id;
            }
            
            if ($report->content->container instanceof User)
                $notification->user_id = $admin->id;
            
            $notification->source_object_model = "ReportContent";
            $notification->source_object_id = $report->id;
            
            $notification->target_object_model = $report->object_model;
            $notification->target_object_id = $report->object_id;
            
            $notification->save();
        }
    }
}
?>