<?php

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $reportedContent \humhub\modules\reportcontent\models\ReportContent[] */

/* @var $pagination \yii\data\Pagination */

use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\Button;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Button::asLink(Icon::get('cog'))
            ->link(['configuration'])
            ->cssClass('pull-right btn btn-default')
            ->tooltip(Yii::t('AdminModule.base', 'Settings')) ?>

        <?= Yii::t('ReportcontentModule.base', 'Manage <strong>Reported Content</strong>') ?>
    </div>
    <div class="panel-body">
        <p>
            <?= Yii::t('ReportcontentModule.base', 'Here you can manage reported content.') ?>
        </p>

        <?= $this->render('/reportContentAdminGrid', ['isAdmin' => 1, 'reportedContent' => $reportedContent, 'pagination' => $pagination]) ?>
    </div>
</div>