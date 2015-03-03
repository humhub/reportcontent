<?php

class SpaceAdminController extends Controller
{
    
    public $subLayout = "application.modules.reportcontent.views.spaceAdmin._layout";
    
    public function filters()
    {
        return array(
            'accessControl'
        ); // perform access control for CRUD operations
    }
    
    public function behaviors()
    {
        return array(
            'ProfileControllerBehavior' => array(
                'class' => 'application.modules_core.space.behaviors.SpaceControllerBehavior',
            ),
        );
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function beforeAction($action)
    {
        if (!$this->getSpace()->isAdmin())
            throw new CHttpException(403, 'Access denied - Space Administrator only!');
        return parent::beforeAction($action);
    }
    
    
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->join = 'inner join content ON content.object_id = t.object_id and t.object_model = content.object_model and content.space_id ='.$this->getSpace()->id;
        
        $reportedContent = ReportContent::model()->findAll($criteria);
        $dataProvider = new CArrayDataProvider($reportedContent, array(
            'id' => 'id',
            'pagination' => array(
                'pageSize' => 5
            )
        ));
        
        $this->render('index', array('reportedContent' => $dataProvider));
         
    }
   
}