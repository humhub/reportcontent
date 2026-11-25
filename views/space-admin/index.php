<?php

/* @var $this \humhub\components\View */
/* @var $reportedContent \humhub\modules\reportcontent\models\ReportContent[] */
/* @var $pagination \yii\data\Pagination */

?>
<div class="card content-card">
    <div class="card-header"><div class="card-title"><?= Yii::t('ReportcontentModule.base', '<strong>Reported</strong> Content') ?></div></div>
    <div class="card-body">
        <p>
            <?= Yii::t('ReportcontentModule.base', 'This overview shows you a list of content that has been reported for various reasons. Please review the content and determine if it meets the community guidelines.') ?>
        </p>

        <?= $this->render('/reportContentAdminGrid', ['isAdmin' => 0, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>
    </div>
</div>