<?php

use humhub\libs\Html;
use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var $content \humhub\modules\content\models\Content
 * @var $object \humhub\modules\content\components\ContentActiveRecord
 * @var $model \humhub\modules\reportcontent\models\ReportReasonForm
 */

?>
<!-- Modal with reasons of report -->
<div class="modal-dialog modal-dialog-small">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">
                <strong>
                    <?= Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'Help Us Understand What\'s Happening'); ?>
                </strong>
            </h4>
        </div>
        <hr/>

        <?php $form = ActiveForm::begin(['id' => 'report-content-form']); ?>
        <?= $form->field($model, 'content_id')->hiddenInput(['value' => $content->id])->label(false); ?>

        <div class="modal-body text-left">
            <?= $form->field($model, 'reason')->radioList($model->getReasonOptions()); ?>
        </div>
        <hr/>

        <div class="modal-footer">
            <a href="#" id="submitReport" class="btn btn-primary" data-ui-loader>
                <?= Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'Submit'); ?>
            </a>
        </div>

        <?php $form::end(); ?>
    </div>
</div>


<script <?= Html::setNonce() ?>>
    $('#submitReport').on('click', function (evt) {
        evt.preventDefault();
        var $form = $(this).closest('form');

        $.ajax('<?= Url::to(['/reportcontent/report-content/report']); ?>', {
            method: 'POST',
            dataType: 'json',
            data: $form.serialize(),
            success: function (result) {
                if (result.success) {
                    $('#globalModal').modal('hide');
                } else {
                    $('#globalModal').html(result.content);
                }
            }
        });
    });
</script>
