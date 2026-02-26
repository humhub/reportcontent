<?php
/* @var $this \humhub\components\View */
/* @var $model \humhub\modules\reportcontent\models\Configuration */

use humhub\widgets\form\ActiveForm;
use humhub\widgets\bootstrap\Button;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Button::light()
            ->icon('back')
            ->link(['index'])
            ->right()
            ->tooltip(Yii::t('AdminModule.base', 'Settings')) ?>

        <?= Yii::t('ReportcontentModule.base', '<strong>Report</strong> Content'); ?>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'profanityFilterList')->textarea(['rows' => 10]); ?>
        <?= $form->field($model, 'blockContributions')->checkbox(); ?>
        <br/>
        <div class="mb-3">
            <?= Button::save()->submit() ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

