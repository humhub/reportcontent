<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'reportedcontent-grid',
    'dataProvider' => $reportedContent,
    'itemsCssClass' => 'table table-hover',
    'ajaxUpdate'=>true,
    'afterAjaxUpdate'=>'shortenContentMessages',
    'columns' => array(
        array(
            'value' => 'CHtml::image($data->getSource()->content->user->profileImage->getUrl(), "48x48", array("style"=>"height:48px; width:48px"))',
            'type' => 'raw',
            'htmlOptions' => array('width' => '48px'),
        ),
        array(
            'name'=>'Post',
            'header' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Content'),
            //call the method 'gridDataColumn' from widget
            'value'=>array($this,'gridContentColumn'),
        ),
        array(
            'name' => 'Reason',
            'header' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Reason'),
            'value' => 'ReportContent::getReason($data->reason)',
            'htmlOptions' => array('style'=> 'font-weight:bold', 'valign' => 'middle')       
        ),
        array(
            'value' => 'CHtml::image($data->content->user->profileImage->getUrl(), "48x48", array("style"=>"height:48px; width:48px"))',
            'type' => 'raw',
            'htmlOptions' => array('width' => '48px'),
        ),
        array(
            'name'=>'Reporter',
            'header' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Reporter'),
            //call the method 'gridDataColumn' from widget
            'value'=>array($this,'gridReporterColumn'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{appropriate}{delete}',
            'htmlOptions' => array('width' => '200px'),
            'buttons' => array(
                'appropriate' => array
                (
                    'label' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Appropriate'),
                    'imageUrl' => false,
                    'options' => array(
                        'style' => 'margin-right: 3px',
                        'class' => 'btn btn-primary btn-sm',
                    ),
                    'click'=>"function(){
                        
                        $.fn.yiiGridView.update('reportedcontent-grid', {
                            type:'POST',
                            url:$(this).attr('href'),
                            data: {".Yii::app()->request->csrfTokenName." : \"".Yii::app()->request->csrfToken."\"},
                            success:function(data) {
                                data = JSON.parse(data);
                                if(data['success'])
                                    $('#reportedcontent-grid').yiiGridView.update('reportedcontent-grid'); 
                            }
                        });
                        return false;
                    }",
                    'url' => 'Yii::app()->createUrl("reportcontent/reportcontent/appropriate", array("id" => $data->id))',
                ),
                'delete' => array
                (
                    'label' => Yii::t('ReportContentModule.widgets_views_reportContentAdminGrid', 'Delete post'),
                    'imageUrl' => false,
                    'options' => array(
                        'style' => 'margin-right: 3px',
                        'class' => 'btn btn-danger btn-sm'
                     ),
                     'click'=>"function(){
                                     
                        $.fn.yiiGridView.update('reportedcontent-grid', {
                        
                            type:'POST',
                            url:$(this).attr('href'),
                            data: {".Yii::app()->request->csrfTokenName." : \"".Yii::app()->request->csrfToken."\"},
                            success:function(data) {
                                data = JSON.parse(data);
                                if(data['success'])
                                $('#reportedcontent-grid').yiiGridView.update('reportedcontent-grid');
                            }
                        });
                        return false;
                    }
                    ",
                    'url' => 'Yii::app()->createUrl("wall/content/delete", array("model"=>$data->object_model, "id" => $data->object_id))',
                ),
            ),
        ),
    ),
    'pager' => array(
        'class' => 'CLinkPager',
        'maxButtonCount' => 5,
        'nextPageLabel' => '<i class="fa fa-step-forward"></i>',
        'prevPageLabel' => '<i class="fa fa-step-backward"></i>',
        'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
        'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
        'header' => '',
        'htmlOptions' => array(
            'class' => 'pagination'
        )
    ),
    'pagerCssClass' => 'pagination-container'
));
?>