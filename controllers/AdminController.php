<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\reportcontent\models\ReportContent;
use yii\data\Pagination;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $query = ReportContent::find();
        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', [
            'reportedContent' => $query->all(),
            'pagination' => $pagination,
        ]);
    }

}
