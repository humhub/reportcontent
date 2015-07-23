<?php

namespace humhub\modules\reportcontent\widgets;

use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\models\ReportReasonForm;

/**
 * ReportContentWidget for reporting a post
 *
 * This widget allows to report a post.
 *
 * @package humhub.modules.reportcontent.widgets
 */
class ReportContentWidget extends \humhub\components\Widget
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
        if ((get_class($this->content) == Post::className()) && ReportContent::canReportPost($this->content->id)) {

            return $this->render('reportSpamLink', array(
                        'object' => $this->content,
                        'model' => new ReportReasonForm()
            ));
        }
    }

}

?>