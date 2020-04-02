<?php

use tests\codeception\fixtures\modules\reportcontent\ReportContentFixture;

return [
    #'humhub_root' => '...',
    'modules' => ['reportcontent'],
    'fixtures' => [
        'default',
        'reportContent' => ReportContentFixture::class,
        'content' => 'tests\codeception\fixtures\modules\reportcontent\ContentFixture',
        'post' => 'tests\codeception\fixtures\modules\reportcontent\PostFixture'
    ]
];



