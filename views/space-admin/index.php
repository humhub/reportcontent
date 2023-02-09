<div class="panel panel-default">
    <div
        class="panel-heading"><?= Yii::t('ReportcontentModule.base', 'Manage <strong>reported posts</strong>') ?></div>

    <div class="panel-body">

        <p>
            <?= Yii::t('ReportcontentModule.views_spaceAdmin_index', 'Here you can manage reported posts for this space.') ?>
        </p>

        <?= $this->render('/reportContentAdminGrid', ['isAdmin' => 0, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>

    </div>
</div>