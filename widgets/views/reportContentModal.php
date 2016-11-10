<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\compat\CActiveForm;
?>
<!-- Modal with reasons of report -->
<div class="modal-dialog modal-dialog-small">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">
                <strong>
<?php echo Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'Help Us Understand What\'s Happening'); ?>
                </strong>
            </h4>
        </div>
        <hr />
        <?php $form = CActiveForm::begin(['id' => 'report-content-form']); ?>
            <?php echo $form->hiddenField($model, 'content_id', ['value' => $content->id]); ?>
        <div class="modal-body text-left">
            <?=
            $form->field($model, 'reason')->radioList($model->getReasonOptions(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return '<label>' . Html::radio($name, $checked, ['value' => $value, 'autocomplete' => 'off']) . $label . '</label><br />';
                        }
                    ]);
                    ?>
                </div>
                <hr />
                <div class="modal-footer">
                    <a href="#" id="submitReport" class="btn btn-primary" data-ui-loader>
                <?= Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'Submit'); ?>
                    </a>
                </div>
        <?php CActiveForm::end(); ?>
            </div>
        </div>



        <script type="text/javascript">
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