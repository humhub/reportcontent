<?php
/* @var $this \humhub\components\View */
/* @var $model \humhub\modules\reportcontent\models\Configuration */

use humhub\widgets\form\ActiveForm;
use humhub\widgets\bootstrap\Button;

?>
<div class="card content-card">
    <div class="card-header">
        <div class="card-title"><?= Yii::t('ReportcontentModule.base', '<strong>Report</strong> Content'); ?></div>
        <div class="card-header-actions">
            <?= Button::light()
                ->icon('back')
                ->link(['index'])
                ->tooltip(Yii::t('AdminModule.base', 'Settings')) ?>
        </div>
    </div>
    <div class="card-body">
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

