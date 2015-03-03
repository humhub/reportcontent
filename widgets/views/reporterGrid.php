<div class="content">
    <strong><?php echo Yii::t('ReportContentModule.base', 'by :displayName', array(':displayName' => CHtml::encode($reportedContent->user->displayName))) ?></strong>
    <br/>
    <small class="media" title="<?php echo $reportedContent->created_at; ?>"><?php echo HHtml::timeago($reportedContent->created_at); ?></small>
</div>