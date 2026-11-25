<?php

use humhub\components\View;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\widgets\bootstrap\Button;
use yii\data\Pagination;

/* @var View $this */
/* @var ReportContent[] $reportedContent */
/* @var Pagination $pagination */
?>
<div class="card content-card">
    <div class="card-header">
        <div class="card-title"><?= Yii::t('ReportcontentModule.base', '<strong>Reported</strong> Content') ?></div>
        <div class="card-header-actions">
            <?= Button::light()
                ->link(['configuration'])
                ->icon('settings')
                ->tooltip(Yii::t('AdminModule.base', 'Settings')) ?>
        </div>
    </div>
    <div class="card-body">
        <?= Yii::t('ReportcontentModule.base', 'This overview shows you a list of content that has been reported for various reasons. Please review the content and determine if it meets the community guidelines.') ?>

        <?= $this->render('/reportContentAdminGrid', ['isAdmin' => 1, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>
    </div>
</div>
