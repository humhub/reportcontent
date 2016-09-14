<?php

return [
    #'humhub_root' => '...',
    'modules' => ['reportcontent'],
    'fixtures' => [
        'default',
        'reportContent' => 'tests\codeception\fixtures\modules\reportcontent\ReportContentFixture',
        'content' => 'tests\codeception\fixtures\modules\reportcontent\ContentFixture',
        'post' => 'tests\codeception\fixtures\modules\reportcontent\PostFixture'
    ]
];



