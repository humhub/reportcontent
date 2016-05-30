<?php

namespace humhub\modules\reportcontent\controllers;

use yii\web\HttpException;
use humhub\modules\reportcontent\models\ReportContent;

class SpaceAdminController extends \humhub\modules\content\components\ContentContainerController
{

    public function beforeAction($action)
    {
        if (!$this->contentContainer->isAdmin()) {
            throw new HttpException(403, 'Access denied - Space Administrator only!');
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $query = ReportContent::find()->joinWith('content')->where(['content.contentcontainer_id' => $this->contentContainer->contentcontainer_id]);
        
        $countQuery = clone $query;
        $pagination = new \yii\data\Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', array(
                    'reportedContent' => $query->all(),
                    'pagination' => $pagination,
        ));
    }

}
