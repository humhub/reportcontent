<div class="panel panel-default">

    <div class="panel-heading"><?php echo Yii::t('ReportcontentModule.base', 'Manage <strong>reported posts</strong>'); ?></div>

    <div class="panel-body">

        <p>
            <?php echo Yii::t('ReportcontentModule.views_admin_index', 'Here you can manage reported users posts.'); ?>
        </p>

        <?php echo $this->render('/reportContentAdminGrid', array('reportedContent' => $reportedContent, 'pagination' => $pagination)) ?>

    </div>
</div>