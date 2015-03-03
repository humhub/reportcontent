<div class="content" style="max-height: 40px; max-width:250px;">              
            
    <p id="content-message-<?php echo $reportedContent->id?>" style="display: inline;" class="contentAnchor"><?php print HHtml::enrichText($reportedContent->getSource()->message) ?></p>
    <br/>    
    <small class="media">
        <span class="time"><?php echo Yii::t('ReporterContent.base', 'created by :displayName', array(':displayName' => CHtml::encode($reportedContent->getSource()->content->user->displayName)))?></span>
        <?php echo HHtml::timeago($reportedContent->getSource()->created_at); ?>
    </small>     
</div>

<script type="text/javascript">

$(document).ready(shortenContentMessages);

function shortenContentMessages(id, data){
    $('.contentAnchor').each(function(){
        var divh=$(this).parent().height();
        var divw=$(this).parent().width();
        
        while ($(this).outerHeight()>divh || $(this).outerWidth()>divw) {
        	$(this).text(function (index, text) {
                return text.replace(/\W*\s(\S)*$/, '...');
            });
        }
    });

    $('.time').each(function(){
        $(this).timeago();
    });
}
</script>