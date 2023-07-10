<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\reportcontent\helpers\Permission;
use humhub\modules\reportcontent\models\ReportContent;
use yii\data\Pagination;

class SpaceAdminController extends ContentContainerController
{

    public function beforeAction($action)
    {
        if (!Permission::canManageReports($this->contentContainer)) {
            $this->forbidden();
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $query = ReportContent::find()->readable($this->contentContainer);

        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', [
            'reportedContent' => $query->all(),
            'pagination' => $pagination,
        ]);
    }

}
