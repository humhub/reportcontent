<?php

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\libs\Html;
use humhub\modules\user\widgets\Image as UserImage;
use humhub\widgets\GridView;
use humhub\widgets\ModalConfirm;
use humhub\widgets\TimeAgo;
use yii\data\ArrayDataProvider;
use yii\grid\DataColumn;
use yii\helpers\Url;

/* @var $reportedContent array */
/* @var $isAdmin boolean */

?>

<?php if (empty($reportedContent)) : ?>
    <br/> <br/>
    <?= Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'There are no reported posts.') ?>
    <br/> <br/>
<?php else : ?>
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider(['allModels' => $reportedContent]),
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'value' => function($report) {
                    return UserImage::widget(['user' => $report->getSource()->content->createdBy, 'width' => 34]);
                }
            ],
            [
                'class' => DataColumn::class,
                'label' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Content'),
                'format' => 'raw',
                'value' => function($report) {
                    $result = Html::tag('p',  RichText::preview($report->getSource()->getContentDescription(), 60));
                    $userLink = Html::a(Html::encode($report->getSource()->content->createdBy->displayName), $report->getSource()->content->createdBy->getUrl());
                    $displayNameLink = Yii::t('ReportcontentModule.base', 'created by :displayName',  [':displayName' => $userLink]);
                    $result .= Html::tag('small', $displayNameLink .' '. TimeAgo::widget(['timestamp' => $report->content->created_at]), ['class' => 'media']);
                    return $result;
                }
            ],
            [
                'class' => DataColumn::class,
                'label' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Reason'),
                'format' => 'raw',
                'value' => function($report) {
                  return '<strong>'.  Html::encode(ReportContent::getReason($report->reason)) . '</strong>';
                }
            ],
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'value' => function($report) {
                    return UserImage::widget(['user' => $report->user, 'width' => 34]);
                }
            ],
            [
                'class' => DataColumn::class,
                'label' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Reporter'),
                'format' => 'raw',
                'value' => function($report) {
                    $result = '<p>'. Html::tag('strong', Html::a(Html::encode($report->user->displayName), $report->user->getUrl())).'</p>';
                    $result .= Html::tag('small', TimeAgo::widget(['timestamp' => $report->created_at]), ['class' => 'media']);
                    return $result;
                }
            ],
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'value' => function($report) use($isAdmin) {
                    $approve = ModalConfirm::widget([
                        'uniqueID' => $report->id,
                        'title' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', '<strong>Approve</strong> content'),
                        'linkTooltipText' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Approve'),
                        'message' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Do you really want to approve this post?'),
                        'buttonTrue' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Approve'),
                        'buttonFalse' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Cancel'),
                        'cssClass' => 'btn btn-success btn-sm tt',
                        'linkContent' => '<i class="fa fa-check-square-o"></i>',
                        'linkHref' => Url::to(["//reportcontent/report-content/appropriate", 'id' => $report->id, 'admin' => $isAdmin]),
                    ]);

                    $review =  Html::a('<i aria-hidden="true" class="fa fa-eye"></i>', $report->content->getUrl(), [
                        'class' => 'btn btn-sm btn-primary tt',
                        'title' =>  Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Review'),
                        'data-ui-loader' => '1'
                    ]);

                    return $approve .' '.$review;
                }
            ],
        ]
    ]) ?>

<?php endif; ?>