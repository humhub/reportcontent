<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\components\Controller;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\models\Content;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\widgets\modal\ModalClose;
use Yii;
use yii\web\NotFoundHttpException;

class ReportController extends Controller
{
    /**
     * @inheritdoc
     */
    protected function getAccessRules()
    {
        return [
            ['login'],
        ];
    }

    public function actionIndex()
    {
        $contentId = (int)Yii::$app->request->get('contentId');
        $commentId = Yii::$app->request->get('commentId');
        $userId = (int)Yii::$app->user->id;

        $content = Content::findOne(['id' => $contentId]);
        if ($content === null || !$content->canView()) {
            throw new NotFoundHttpException();
        }

        if (!empty($commentId)) {
            $comment = Comment::findOne(['id' => $commentId]);
            if ($comment === null || $comment->getContent()?->id != $contentId) {
                throw new NotFoundHttpException();
            }
        }

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

        $reportId = (int)Yii::$app->request->get('id');
        $report = ReportContent::findOne(['id' => $reportId]);

        if ($report === null || $report->content === null) {
            throw new NotFoundHttpException();
        }

        $container = $report->content->getContainer();

        if ($report->canDelete()) {
            $report->delete();
        } else {
            $this->view->warn(Yii::t('ReportcontentModule.base', 'Could not delete Report!'));
        }

        return $this->htmlRedirect(Yii::$app->request->get('admin')
            ? ['/reportcontent/admin']
            : $container->createUrl('/reportcontent/space-admin'));
    }

}
