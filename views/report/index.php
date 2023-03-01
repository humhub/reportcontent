<?php

use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;

/**
 * @var $content \humhub\modules\content\models\Content
 * @var $object \humhub\modules\content\components\ContentActiveRecord
 * @var $model ReportContent
 */

?>

<?php ModalDialog::begin(['header' => Yii::t('ReportcontentModule.base', '<strong>Report</strong> Content')]); ?>
<?php $form = ActiveForm::begin(['id' => 'report-content-form']); ?>
<div class="modal-body">
    <?= $form->field($model, 'content_id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'comment_id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'reason')->radioList($model::getReasons(true)); ?>
</div>
<div class="modal-footer">
    <?= ModalButton::submitModal() ?>
    <?= ModalButton::cancel() ?>
</div>
<?php ActiveForm::end() ?>
<?php ModalDialog::end(); ?>
