<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\reportcontent\Module;
use Yii;
use yii\data\Pagination;

/**
 *
 * @property Module $module
 */
class AdminController extends Controller
{
    public function actionIndex()
    {
        $query = ReportContent::find()->readable();
        $countQuery = clone $query;
        $pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', [
            'reportedContent' => $query->all(),
            'pagination' => $pagination,
        ]);
    }

    public function actionConfiguration()
    {
        $model = $this->module->getConfiguration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->view->saved();
            return $this->redirect(['configuration']);
        }

        return $this->render('configuration', [
            'model' => $model,
        ]);
    }

}
