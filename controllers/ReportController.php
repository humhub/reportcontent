<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\modules\content\permissions\ManageContent;
use humhub\modules\reportcontent\widgets\ReportContentModal;
use humhub\widgets\ModalClose;
use Yii;
use yii\helpers\Url;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\content\models\Content;

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
            return ModalClose::widget(['success' => Yii::t('ReportcontentModule.base', 'Thank you for the report.')]);
        }

        return $this->renderAjax('index', ['model' => $model]);
    }

    public function actionAppropriate()
    {
        $this->forcePostRequest();

        $reportId = Yii::$app->request->get('id');
        $report = ReportContent::findOne(['id' => $reportId]);

        $container = $report->content->getContainer();

        if ($report->canDelete()) {
            $report->delete();
        }

        if (Yii::$app->request->get('admin')) {
            return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
        } else {
            return $this->htmlRedirect($container->createUrl('/reportcontent/space-admin'));
        }
    }

    public function actionDeleteContent()
    {
        $this->forcePostRequest();

        $model = Yii::$app->request->get('model');
        $id = Yii::$app->request->get('id');

        $content = Content::get($model, $id);

        $container = $content->content->getContainer();

        if (Yii::$app->user->identity->isSystemAdmin() ||
            $content->content->getContainer()->permissionManager->can(new ManageContent())) {
            $content->delete();
        }

        if (Yii::$app->request->get('admin')) {
            return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
        } else {
            return $this->htmlRedirect($container->createUrl('/reportcontent/space-admin'));
        }
    }

}

?>