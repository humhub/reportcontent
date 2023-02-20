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
                'value' => function ($report) {
                    return UserImage::widget(['user' => $report->content->createdBy, 'width' => 34]);
                }
            ],
            [
                'class' => DataColumn::class,
                'label' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Content'),
                'format' => 'raw',
                'value' => function ($report) {
                    $result = Html::tag('p', RichText::preview($report->content->getModel()->getContentDescription(), 60));
                    $userLink = Html::a(Html::encode($report->content->createdBy->displayName), $report->content->createdBy->getUrl());
                    $displayNameLink = Yii::t('ReportcontentModule.base', 'created by :displayName', [':displayName' => $userLink]);
                    $result .= Html::tag('small', $displayNameLink . ' ' . TimeAgo::widget(['timestamp' => $report->content->created_at]), ['class' => 'media']);
                    return $result;
                }
            ],
            [
                'class' => DataColumn::class,
                'label' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Reason'),
                'format' => 'raw',
                'value' => function ($report) {
                    return '<strong>' . Html::encode(ReportContent::getReason($report->reason)) . '</strong>';
                }
            ],
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'value' => function ($report) {
                    return UserImage::widget(['user' => $report->user, 'width' => 34]);
                }
            ],
            [
                'class' => DataColumn::class,
                'label' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Reporter'),
                'format' => 'raw',
                'value' => function ($report) {
                    $result = '<p>' . Html::tag('strong', Html::a(Html::encode($report->user->displayName), $report->user->getUrl())) . '</p>';
                    $result .= Html::tag('small', TimeAgo::widget(['timestamp' => $report->created_at]), ['class' => 'media']);
                    return $result;
                }
            ],
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'value' => function ($report) use ($isAdmin) {
                    $approve = Html::a(
                        '<i class="fa fa-check-square-o"></i>', ['/reportcontent/report/appropriate', 'id' => $report->id, 'admin' => $isAdmin],
                        ['data-method' => 'POST', 'class' => 'btn btn-success btn-sm tt', 'data-original-title' => 'Approve']
                    );

                    $review = Html::a('<i aria-hidden="true" class="fa fa-eye"></i>', $report->content->getUrl(), [
                        'class' => 'btn btn-sm btn-primary tt',
                        'title' => Yii::t('ReportcontentModule.widgets_views_reportContentAdminGrid', 'Review'),
                        'data-ui-loader' => '1'
                    ]);

                    return $approve . ' ' . $review;
                }
            ],
        ]
    ]) ?>

<?php endif; ?>