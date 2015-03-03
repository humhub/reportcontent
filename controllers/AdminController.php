<?php

class AdminController extends Controller
{
     public $subLayout = "application.modules_core.admin.views._layout";
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Configuration Action for Super Admins
     */
    public function actionIndex() {

        $criteria = new CDbCriteria;
        $criteria->join = 'inner join content ON content.object_id = t.object_id and t.object_model = content.object_model and content.space_id is null';
        
        $reportedContent = ReportContent::model()->findAll($criteria);
        $dataProvider = new CArrayDataProvider($reportedContent, array(
            'id' => 'id',
            'pagination' => array(
                'pageSize' => 20
            )
        ));
        
        $this->render('index', array('reportedContent' => $dataProvider));
    }
     
}