<?php
/* @var $this \humhub\components\View */
/* @var $model \humhub\modules\reportcontent\models\Configuration */

use humhub\widgets\form\ActiveForm;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\helpers\Html;
use humhub\widgets\bootstrap\Button;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Button::asLink(Icon::get('back'))
            ->link(['index'])
            ->cssClass('float-end btn btn-light')
            ->tooltip(Yii::t('AdminModule.base', 'Settings')) ?>

        <?= Yii::t('ReportcontentModule.base', '<strong>Report</strong> Content'); ?>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'profanityFilterList')->textarea(['rows' => 10]); ?>
        <?= $form->field($model, 'blockContributions')->checkbox(); ?>
        <br/>
        <div class="mb-3">
            <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

