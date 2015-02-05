<?php

/**
 * Defines report post actions
 *
 * @package humhub.modules.reportcontent.controllers
 * @author Marjana Pesic
 */
class ReportContentController extends Controller
{

    /**
     *
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl'
        ); // perform access control for CRUD operations
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * 
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array(
                    '@'
                )
            ),
            array(
                'deny', // deny all users
                'users' => array(
                    '*'
                )
            )
        );
    }

    /**
     * Handles AJAX Post Request to submit new ReportContent
     */
    public function actionReport()
    {
        $this->forcePostRequest();
        
        $json = array();
        $json['success'] = false;
        
        $form = new ReportReasonForm();
        
        if (isset($_POST['ReportReasonForm'])) {
            $_POST['ReportReasonForm'] = Yii::app()->input->stripClean($_POST['ReportReasonForm']);
            $form->attributes = $_POST['ReportReasonForm'];
            
            if ($form->validate() && ReportContent::canReportPost($form->object_id)) {
                
                $report = new ReportContent();
                $report->created_by = Yii::app()->user->id;
                $report->reason = $form->reason;
                $report->object_model = 'Post';
                $report->object_id = $form->object_id;
                $report->save();
                
                $json['success'] = true;
            }
        }
        
        echo CJSON::encode($json);
        Yii::app()->end();
    }
}
?>