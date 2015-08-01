<?php

namespace humhub\modules\reportcontent;

/**
 * ReportContentModule is responsible for allowing useres to report posts.
 *
 * @author Marjana Pesic
 *
 */
class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public function disable()
    {
        foreach (models\ReportContent::find()->all() as $reportContent) {
            $reportContent->delete();
        }

        parent::disable();
    }

}

?>