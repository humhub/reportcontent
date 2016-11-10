<?php

namespace humhub\modules\reportcontent\widgets;

use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\reportcontent\models\ReportReasonForm;

/**
 * ReportContentWidget for reporting a post
 *
 * This widget allows to report a post.
 *
 * @package humhub.modules.reportcontent.widgets
 */
class ReportContentModal extends \humhub\components\Widget
{

    /**
     * Content Object with SIContentBehaviour
     * 
     * @var type
     */
    public $post;

    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->post instanceof ContentActiveRecord && ReportContent::canReportPost($this->post)) {

            return $this->render('reportContentModal', [
                        'object' => $this->post,
                        'content' => $this->post->content,
                        'model' => new ReportReasonForm()
            ]);
        }
    }

}

?>