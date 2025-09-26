<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\modules\reportcontent\models\ReportContent;
use humhub\widgets\modal\ModalClose;
use Yii;
use yii\helpers\Url;

class ReportController extends \humhub\components\Controller
{
    public function actionIndex()
    {
        $contentId = (int)Yii::$app->request->get('contentId');
        $commentId = Yii::$app->request->get('commentId');
        $userId = (int)Yii::$app->user->id;

        $model = ReportContent::findOne(['content_id' => $contentId, 'comment_id' => $commentId, 'created_by' => $userId]);
        if ($model === null) {
            $model = new ReportContent();
            $model->content_id = $contentId;
            $model->comment_id = $commentId;
            $model->created_by = $userId;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ModalClose::widget(['success' => Yii::t('ReportcontentModule.base', 'Content successfully reported.')]);
        }

        return $this->renderAjax('index', ['model' => $model]);
    }

    public function actionAppropriate()
    {
        $this->forcePostRequest();

        $reportId = Yii::$app->request->get('id');
        $report = ReportContent::findOne(['id' => $reportId]);

        $container = $report->content->getContainer();

        if ($report->canDelete(Yii::$app->user->getIdentity())) {
            $report->delete();
        } else {
            $this->view->warn(Yii::t('ReportcontentModule.base', 'Could not delete Report!'));

        }

        if (Yii::$app->request->get('admin')) {
            return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
        } else {
            return $this->htmlRedirect($container->createUrl('/reportcontent/space-admin'));
        }
    }

}
