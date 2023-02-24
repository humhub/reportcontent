<?php

use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\ui\form\widgets\ActiveForm;

/**
 * @var $content \humhub\modules\content\models\Content
 * @var $object \humhub\modules\content\components\ContentActiveRecord
 * @var $model ReportContent
 */

?>
<!-- Modal with reasons of report -->
<div class="modal-dialog modal-dialog-small">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">
                <strong>
                    <?= Yii::t('ReportcontentModule.base', 'Help Us Understand What\'s Happening'); ?>
                </strong>
            </h4>
        </div>
        <hr/>

        <?php $form = ActiveForm::begin(['id' => 'report-content-form']); ?>
        <?= $form->field($model, 'content_id')->hiddenInput()->label(false); ?>
        <?= $form->field($model, 'comment_id')->hiddenInput()->label(false); ?>

        <div class="modal-body text-left">
            <?= $form->field($model, 'reason')->radioList($model::getReasons(true)); ?>
        </div>
        <hr/>

        <div class="modal-footer">
            <a href="#" id="submitReport" class="btn btn-primary" data-ui-loader data-action-click="ui.modal.submit"
               data-action-submit>
                <?= Yii::t('ReportcontentModule.base', 'Submit'); ?>
            </a>
        </div>

        <?php $form::end(); ?>
    </div>
</div>

