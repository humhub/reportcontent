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
        
        $form = new ReportReasonForm();
        
        $json = [];
        if($form->load(Yii::$app->request->post()) && $form->save()) {
            $json['success'] = true;
        } else {
            $post = $form->getPostModel();
            
            $json['success'] = false;
            $json['content'] = \humhub\modules\reportcontent\widgets\ReportContentModal::widget([
                'post' => $post
            ]);
        }
        

        return $json;
    }

    public function actionAppropriate()
    {
        $this->forcePostRequest();

        $reportId = Yii::$app->request->get('id');
        $report = ReportContent::findOne(['id' => $reportId]);

        if (version_compare(Yii::$app->version, '1.1', 'lt')) {
            if ($report->canDelete()) {
                $report->delete();
            }
            
            if (Yii::$app->request->get('admin')) {
                return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
            } else {
                $space = Space::findOne(['id' => $report->content->space_id]);
                return $this->htmlRedirect($space->createUrl('/reportcontent/space-admin'));
            }
        } else {
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
    }

    public function actionDeleteContent()
    {
        $this->forcePostRequest();

        $model = Yii::$app->request->get('model');
        $id = Yii::$app->request->get('id');

        $content = Content::get($model, $id);
        $isSystemAdmin = Yii::$app->user->getIdentity()->super_admin;

        if (version_compare(Yii::$app->version, '1.1', 'lt')) {
            if ($isSystemAdmin || $content->content->canDelete()) {
                $content->delete();
            }

            if (Yii::$app->request->get('admin')) {
                return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
            } else {
                $space = Space::findOne(['id' => $content->content->space_id]);
                return $this->htmlRedirect($space->createUrl('/reportcontent/space-admin'));
            }
        } else {
            $container = $content->content->getContainer();
            
            if ($isSystemAdmin || $content->content->getContainer()->permissionManager->can(new \humhub\modules\content\permissions\ManageContent())) {
                $content->delete();
            }
            
            if (Yii::$app->request->get('admin')) {
                return $this->htmlRedirect(Url::to(['/reportcontent/admin']));
            } else {
                return $this->htmlRedirect($container->createUrl('/reportcontent/space-admin'));
            }
        }
    }

}

?>