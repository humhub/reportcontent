<?php

class ReportContentAdminGrid extends HWidget
{
    
    public $reportedContent;


    /**
     * Executes the widget.
     */
    public function run()
    {
        $this->render('reportContentAdminGrid', array('reportedContent' => $this->reportedContent));
    }
    
    protected function gridContentColumn($data,$row)
    {  
        return $this->render('contentGrid', array('reportedContent' => $data));
    }
    
    protected function gridReporterColumn($data, $row)
    {
        return $this->render('reporterGrid', array('reportedContent' => $data));
    }
}