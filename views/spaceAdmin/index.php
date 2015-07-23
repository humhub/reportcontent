<div class="panel panel-default">
    <div
        class="panel-heading"><?php echo Yii::t('ReportContentModule.base', 'Manage <strong>reported posts</strong>'); ?></div>
    
    <div class="panel-body">

        <p>
            <?php echo Yii::t('ReportContentModule.views_spaceAdmin_index', 'Here you can manage reported posts for this space.'); ?>
        </p>

         <?php $this->renderPartial('/reportContentAdminGrid', array('reportedContent' => $reportedContent, 'pages' => $pages, 'item_count' => $item_count,
            'page_size' => $page_size))?>

    </div>
</div>