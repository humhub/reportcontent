<?php

namespace humhub\modules\reportcontent\controllers;

use humhub\modules\content\permissions\ManageContent;
use humhub\modules\reportcontent\widgets\ReportContentModal;
use Yii;
use yii\helpers\Url;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\reportcontent\models\ReportReasonForm;
use humhub\modules\content\models\Content;

class ReportContentController extends \humhub\components\Controller
{

    /**
     * Handles AJAX Post Request to submit new ReportContent
     */
    public function actionReport()
    {
        $this->forcePostRequest();

        $form = new ReportReasonForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            return $this->asJson(['success' => true]);
        }

        return $this->asJson([
            'content' => ReportContentModal::widget(['post' => $form->getPostModel()])
        ]);
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