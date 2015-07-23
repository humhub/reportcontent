<div class="panel panel-default">
    <div
        class="panel-heading"><?php echo Yii::t('ReportcontentModule.base', 'Manage <strong>reported posts</strong>'); ?></div>

    <div class="panel-body">

        <p>
            <?php echo Yii::t('ReportcontentModule.views_spaceAdmin_index', 'Here you can manage reported posts for this space.'); ?>
        </p>

        <?php echo $this->render('/reportContentAdminGrid', array('reportedContent' => $reportedContent, 'pagination' => $pagination)) ?>

    </div>
</div>