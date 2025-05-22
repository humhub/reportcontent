<?php

/* @var $this \humhub\components\View */
/* @var $reportedContent \humhub\modules\reportcontent\models\ReportContent[] */

/* @var $pagination \yii\data\Pagination */

use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\bootstrap\Button;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Button::asLink(Icon::get('cog'))
            ->link(['configuration'])
            ->cssClass('float-end btn btn-light')
            ->tooltip(Yii::t('AdminModule.base', 'Settings')) ?>

        <?= Yii::t('ReportcontentModule.base', '<strong>Reported</strong> Content') ?>
    </div>
    <div class="panel-body">
        <?= Yii::t('ReportcontentModule.base', 'This overview shows you a list of content that has been reported for various reasons. Please review the content and determine if it meets the community guidelines.') ?>

        <?= $this->render('/reportContentAdminGrid', ['isAdmin' => 1, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>
    </div>
</div>
