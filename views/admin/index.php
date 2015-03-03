<div class="panel panel-default">

    <div class="panel-heading"><?php echo Yii::t('ReportContentModule.base', 'Manage <strong>reported posts</strong>'); ?></div>
    
    <div class="panel-body">

        <p>
            <?php echo Yii::t('ReportContentModule.views_admin_index', 'Here you can manage reported users posts.'); ?>
        </p>
        
        <?php $this->widget('application.modules.reportcontent.widgets.ReportContentAdminGrid', array('reportedContent' => $reportedContent))?>

    </div>
</div>