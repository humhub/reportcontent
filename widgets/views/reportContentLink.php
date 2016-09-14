<?php

use yii\helpers\Url;
?>
<!-- Link in menu for reporting the post -->
<li>
    <a data-content-id="<?= $content->id ?>" href="#" class="reportContentLink"> 
        <?php echo '<i class="fa fa-exclamation-circle"></i> ' . Yii::t('ReportcontentModule.widgets_views_reportSpamLink', 'Report post'); ?>
    </a> 
</li>

<script>
    $('.reportContentLink').off('click').on('click', function (evt) {
        evt.preventDefault();
        
        var contentId = $(this).data('content-id');
        $.ajax('<?= Url::to(['/reportcontent/report-content/report']); ?>', {
            method: 'POST',
            dataType: 'json',
            data: {
                '<?= $model->formName() ?>[content_id]': contentId
            },
            beforeSend: function () {
                setModalLoader();
                $('#globalModal').modal('show');
            },
            success: function (result) {
                $('#globalModal').html(result.content);
            }

        });

    });
</script>
