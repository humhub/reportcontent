<div class="panel panel-default">

    <div class="panel-heading"><?= Yii::t('ReportcontentModule.base', 'Manage <strong>reported posts</strong>') ?></div>

    <div class="panel-body">

        <p>
            <?= Yii::t('ReportcontentModule.views_admin_index', 'Here you can manage reported users posts.') ?>
        </p>

        <?= $this->render('/reportContentAdminGrid', ['isAdmin' => 1, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>

    </div>
</div>