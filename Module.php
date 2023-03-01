<?php

namespace humhub\modules\reportcontent;

use humhub\modules\reportcontent\models\Configuration;
use yii\helpers\Url;

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
    public $resourcesPath = 'resources';

    private ?Configuration $_configuration = null;

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

    public function getConfigUrl()
    {
        return Url::to(['/reportcontent/admin/configuration']);
    }

    /**
     * @inheritdoc
     */
    public function getConfiguration(): Configuration
    {
        if ($this->_configuration === null) {
            $this->_configuration = new Configuration(['settingsManager' => $this->settings]);
            $this->_configuration->loadBySettings();
        }
        return $this->_configuration;
    }
}

?>