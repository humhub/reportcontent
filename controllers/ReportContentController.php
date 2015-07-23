<?php

namespace humhub\modules\reportcontent\controllers;

use Yii;
use yii\helpers\Url;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\reportcontent\models\ReportReasonForm;
use humhub\modules\space\models\Space;
use humhub\modules\content\models\Content;

/**
 * Defines report post actions
 *
 * @package humhub.modules.reportcontent.controllers
 * @author Marjana Pesic
 */
class ReportContentController extends \humhub\components\Controller
{

    /**
     * Handles AJAX Post Request to submit new ReportContent
     */
    public function actionReport()
    {
        $this->forcePostRequest();

        Yii::$app->response->format = 'json';

        $json = array();
        $json['success'] = false;

        $form = new ReportReasonForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate() && ReportContent::canReportPost($form->object_id)) {
            $report = new ReportContent();
            $report->created_by = Yii::$app->user->id;
            $report->reason = $form->reason;
            $report->object_model = Post::className();
            $report->object_id = $form->object_id;
            if ($report->save()) {
                $json['success'] = true;
            }
        }

        return $json;
    }

    public function actionAppropriate()
    {
        $this->forcePostRequest();

        $reportId = Yii::$app->request->get('id');
        $report = ReportContent::findOne(['id' => $reportId]);
        if ($report->canDelete())
            $report->delete();

        if (!$report->content->space_id)
            return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
        else {
            $space = Space::findOne(['id' => $report->content->space_id]);
            return $this->htmlRedirect($space->createUrl('/reportcontent/space-admin'));
        }
    }

    public function actionDeleteContent()
    {
        $this->forcePostRequest();

        $model = Yii::$app->request->get('model');
        $id = Yii::$app->request->get('id');

        $content = Content::get($model, $id);

        if ($content->content->canDelete())
            $content->delete();

        if (!$content->content->space_id)
            return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
        else {
            $space = Space::findOne(['id' => $content->content->space_id]);
            return $this->htmlRedirect($space->createUrl('/reportcontent/space-admin'));
        }
    }

}

?>