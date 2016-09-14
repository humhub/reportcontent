<?php

namespace humhub\modules\reportcontent\controllers;

use Yii;
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
        if (version_compare(Yii::$app->version, '1.1', 'lt')) {
            $query = ReportContent::find()->joinWith('content')
                ->where(['content.space_id' => $this->contentContainer->contentcontainer_id,])
                ->andWhere(['not', ['content.created_by' => Yii::$app->user->getIdentity()->id]])
                ->andWhere(['system_admin_only' => 0]);
        } else {
            $query = ReportContent::find()->joinWith('content')
                ->where(['content.contentcontainer_id' => $this->contentContainer->contentcontainer_id,])
                ->andWhere(['not', ['content.created_by' => Yii::$app->user->getIdentity()->id]])
                ->andWhere(['system_admin_only' => 0]);
        }
        $countQuery = clone $query;
        $pagination = new \yii\data\Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        return $this->render('index', array(
                    'reportedContent' => $query->all(),
                    'pagination' => $pagination,
        ));
    }

}
