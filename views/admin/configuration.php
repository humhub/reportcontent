<?php
/* @var $this \humhub\modules\ui\view\components\View */
/* @var $model \humhub\modules\reportcontent\models\Configuration */

use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\libs\Html;
use humhub\widgets\Button;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Button::asLink(Icon::get('back'))
            ->link(['index'])
            ->cssClass('pull-right btn btn-default')
            ->tooltip(Yii::t('AdminModule.base', 'Settings')) ?>

        <?= Yii::t('ReportcontentModule.base', '<strong>Report</strong> Content'); ?>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'profanityFilterList')->textarea(['rows' => 10]); ?>
        <?= $form->field($model, 'blockContributions')->checkbox(); ?>
        <br/>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

