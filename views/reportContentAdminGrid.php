<?php if(count($reportedContent) == 0):?>
    <br/> <br/>
    <?php echo Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'There are no reported posts.')?>
    <br/> <br/>
<?php endif; ?>

<?php if (count($reportedContent) > 0): ?>

        <table class="table table-hover">
            <thead>
            <tr>
                <th></th>
                <th><?php echo Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Content'); ?></th>
                <th><?php echo Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Reason'); ?></th>
                <th></th>
                <th><?php echo Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Reporter'); ?></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reportedContent as $report) : ?>
                <tr>
                    <td width="52px">
                        <a href="<?php echo $report->getSource()->content->user->getUrl(); ?>">

                            <img class="media-object img-rounded"
                                 src="<?php echo $report->getSource()->content->user->profileImage->getUrl(); ?>" width="48"
                                 height="48" alt="48x48" data-src="holder.js/48x48"
                                 style="width: 48px; height: 48px;">
                        </a>
                    </td>
                    <td>
                        <div class="content" style="max-height: 40px; max-width:250px;">              
                
                            <p id="content-message-<?php echo $report->id?>" style="display: inline;" class="contentAnchor"><?php print HHtml::enrichText($report->getSource()->message) ?></p>
                            <br/>    
                            <small class="media">
                                <span class="time"><?php echo Yii::t('ReporterContent.base', 'created by :displayName', array(':displayName' => CHtml::link(CHtml::encode($report->getSource()->content->user->displayName),$report->getSource()->content->user->getUrl())))?></span>
                                <?php echo HHtml::timeago($report->getSource()->created_at); ?>
                            </small>     
                        </div>
                    </td>
                    <td style="font-weight:bold; vertical-align:middle">
                        <?php echo ReportContent::getReason($report->reason);?>
                    </td>
                    <td width="52px">
                        <a href="<?php echo $report->user->getUrl(); ?>">

                            <img class="media-object img-rounded"
                                 src="<?php echo $report->user->profileImage->getUrl(); ?>" width="48"
                                 height="48" alt="48x48" data-src="holder.js/48x48"
                                 style="width: 48px; height: 48px;">
                        </a>
                    </td>
                    <td>
                        <div class="content">
                            <strong><?php echo Yii::t('ReportContentModule.base', 'by :displayName', array(':displayName' => CHtml::link(CHtml::encode($report->user->displayName), $report->user->getUrl()))) ?></strong>
                            <br/>
                            <small class="media" title="<?php echo $report->created_at; ?>"><?php echo HHtml::timeago($report->created_at); ?></small>
                        </div>
                    </td>
                    <td style="vertical-align:middle">
                        <?php
                            $this->widget('application.widgets.ModalConfirmWidget', array(
                                'uniqueID' => 'delete_'.$report->id,
                                'title' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', '<strong>Confirm</strong> report deletion'),
                                'message' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Do you really want to delete this report?'),
                                'buttonTrue' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Delete'),
                                'buttonFalse' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Cancel'),
                                'class' => 'btn btn-primary btn-sm',
                                'linkContent' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Delete report'),
                                'linkHref' => Yii::app()->getController()->createUrl("//reportcontent/reportcontent/appropriate", array('id' => $report->id)),
                                'confirmJS' => 'function(jsonResp) { location.reload();  }'
                            ));
                            ?>
                    </td>
                    <td style="vertical-align:middle">
                        <?php
                            $this->widget('application.widgets.ModalConfirmWidget', array(
                                'uniqueID' => $report->id,
                                'title' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', '<strong>Confirm</strong> post deletion'),
                                'message' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Do you really want to delete this post? All likes and comments will be lost!'),
                                'buttonTrue' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Delete'),
                                'buttonFalse' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Cancel'),
                                'class' => 'btn btn-sm btn-danger',
                                'linkContent' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Delete post'),
                                'linkHref' => Yii::app()->getController()->createUrl("//wall/content/delete", array('model' => get_class($report->getSource()), 'id' => $report->getSource()->id)),
                                'confirmJS' => 'function(jsonResp) { location.reload();  }'
                            ));
                            ?>
                    </td>
            <?php endforeach;?>
            </tbody>
        </table>
        
        
        
        <div class="pagination-container">
            <?php
            $this->widget('CLinkPager', array(
                'currentPage' => $pages->getCurrentPage(),
                'itemCount' => $item_count,
                'pageSize' => $page_size,
                'maxButtonCount' => 5,
                'nextPageLabel' => '<i class="fa fa-step-forward"></i>',
                'prevPageLabel' => '<i class="fa fa-step-backward"></i>',
                'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
                'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
                'header' => '',
                'htmlOptions' => array('class' => 'pagination'),
            ));
            ?>
        </div>
        
<?php endif; ?>

<script type="text/javascript">

$(document).ready(function(){
    $('.contentAnchor').each(function(){
        var divh=$(this).parent().height();
        var divw=$(this).parent().width();
        
        while ($(this).outerHeight()>divh || $(this).outerWidth()>divw) {
        	$(this).text(function (index, text) {
                return text.replace(/\W*\s(\S)*$/, '...');
            });
        }
    });
});
</script>