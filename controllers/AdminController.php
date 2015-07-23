<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\modules\reportcontent\models\ReportContent;

class AdminController extends \humhub\modules\admin\components\Controller
{

    /**
     * Configuration Action for Super Admins
     */
    public function actionIndex()
    {
        $query = ReportContent::find()->joinWith('content')->andWhere(['IS', 'content.space_id', new \yii\db\Expression('NULL')]);

        $countQuery = clone $query;
        $pagination = new \yii\data\Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', array(
                    'reportedContent' => $query->all(),
                    'pagination' => $pagination,
        ));
    }

}
