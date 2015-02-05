<?php

/**
 * ReportContentWidget for reporting a post
 *
 * This widget allows to report a post.
 *
 * @package humhub.modules.reportcontent.widgets
 */
class ReportContentWidget extends HWidget
{

    /**
     * Content Object with SIContentBehaviour
     * 
     * @var type
     */
    public $content;

    /**
     * Executes the widget.
     */
    public function run()
    {
        if (ReportContent::canReportPost($this->content->id)) {
            
            $this->render('reportSpamLink', array(
                'object' => $this->content,
                'model' => new ReportReasonForm()
            ));
        }
    }
}
?>