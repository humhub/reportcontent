<?php

use humhub\modules\reportcontent\models\ReportContent;
use humhub\widgets\form\ActiveForm;
use humhub\widgets\modal\ModalButton;
use humhub\widgets\modal\Modal;

/**
 * @var $content \humhub\modules\content\models\Content
 * @var $object \humhub\modules\content\components\ContentActiveRecord
 * @var $model ReportContent
 */

?>

<?php $form = Modal::beginFormDialog([
        'title' => Yii::t('ReportcontentModule.base', '<strong>Report</strong> Content'),
        'footer' => ModalButton::cancel() . ModalButton::save(Yii::t('ReportcontentModule.base', 'Send')),
        'form' => ['id' => 'report-content-form'],
    ]); ?>
    <?= $form->field($model, 'content_id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'comment_id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'reason')->radioList($model->getReasons(true)); ?>
<?php Modal::endFormDialog(); ?>
