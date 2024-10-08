<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace tests\codeception\fixtures;

use yii\test\ActiveFixture;

class ReportContentFixture extends ActiveFixture
{
    public $modelClass = 'humhub\modules\reportcontent\models\ReportContent';
    public $dataFile = '@reportcontent/tests/codeception/fixtures/data/reportContent.php';

}
