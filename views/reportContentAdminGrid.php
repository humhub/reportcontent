<?php

use humhub\modules\comment\models\Comment;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\content\widgets\richtext\converter\RichTextToShortTextConverter;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\helpers\Html;
use humhub\modules\user\widgets\Image as UserImage;
use humhub\widgets\GridView;
use humhub\widgets\bootstrap\Alert;
use yii\data\ArrayDataProvider;
use yii\grid\DataColumn;

/* @var $reportedContent array */
/* @var $isAdmin boolean */

?>

<?php if (empty($reportedContent)) : ?>
    <?= Alert::success(Yii::t('ReportcontentModule.base', 'There is no content reported for review.')) ?>
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
                'label' => Yii::t('ReportcontentModule.base', 'Content'),
                'format' => 'raw',
                'value' => function (ReportContent $report) {
                    /** @var ContentActiveRecord|Comment $reportedRecord */
                    $reportedRecord = null;
                    if (!empty($report->comment_id)) {
                        $reportedRecord = Comment::findOne(['id' => $report->comment_id]);
                    } else {
                        $reportedRecord = Content::findOne(['id' => $report->content_id])->getModel();
                    }

                    $result = Html::beginTag('p');
                    $result .= Html::encode($reportedRecord->getContentName()) . ': ';
                    $result .= RichTextToShortTextConverter::process(
                        $reportedRecord->getContentDescription(),
                        [RichTextToShortTextConverter::OPTION_MAX_LENGTH => 200]
                    );
                    $result .= Html::endTag('p');

                    $userLink = Html::a(Html::encode($report->content->createdBy->displayName), $report->content->createdBy->getUrl());

                    $result .= Html::beginTag('small');
                    $result .= Yii::t('ReportcontentModule.base', 'Created by {author} at {dateTime}. Reported by {reporter}.',
                        [
                            'author' => $userLink,
                            'dateTime' => Yii::$app->formatter->asDateTime($report->content->created_at, 'short'),
                            'reporter' => ($report->user) ? Html::a(Html::encode($report->user->displayName), $report->user->getUrl()) : '-'
                        ]
                    );


                    return $result;
                }
            ],
            [
                'class' => DataColumn::class,
                'label' => Yii::t('ReportcontentModule.base', 'Reason'),
                'options' => ['style' => 'width:120px;'],
                'format' => 'raw',
                'value' => function (ReportContent $report) {
                    return '<strong>' . Html::encode($report->getReason()) . '</strong>';
                }
            ],
            [
                'class' => DataColumn::class,
                'format' => 'raw',
                'options' => ['style' => 'width:85px;'],
                'value' => function ($report) use ($isAdmin) {
                    $approve = Html::a(
                        '<i class="fa fa-check-square-o"></i>', ['/reportcontent/report/appropriate', 'id' => $report->id, 'admin' => $isAdmin],
                        ['data-method' => 'POST', 'class' => 'btn btn-success btn-sm tt', 'data-original-title' => 'Approve']
                    );

                    $review = Html::a('<i aria-hidden="true" class="fa fa-eye"></i>', $report->content->getUrl(), [
                        'class' => 'btn btn-sm btn-primary tt',
                        'title' => Yii::t('ReportcontentModule.base', 'Review'),
                        'data-ui-loader' => '1'
                    ]);

                    return $approve . ' ' . $review;
                }
            ],
        ]
    ]) ?>

<?php endif; ?>
