<?php

namespace reportcontent\functional;

use humhub\modules\content\models\Content;
use humhub\modules\notification\models\Notification;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\reportcontent\notifications\NewReportAdmin;
use humhub\modules\space\models\Space;
use reportcontent\FunctionalTester;

/**
 * Tests for Notifications and Visibility (Flag for GlobalAdmins)
 */
class VisibilityCest
{
    public function testReportAgainstSpaceMember(FunctionalTester $I)
    {
        $space2 = Space::findOne(['id' => 2]);

        // Post by  Space NonMember
        $I->amUser2();
        $post = new Post($space2, Content::VISIBILITY_PUBLIC, ['message' => 'Some bad post by regular space member']);
        $post->content->created_by = 3;
        $post->save();

        // Report by Space NonMember
        $I->amUser3(true);
        $report = new ReportContent(['content_id' => $post->content->id, 'created_by' => 4, 'reason' => 1]);
        $report->save();

        // Space Owner
        $I->amUser1(true);
        $I->amOnSpace2('/reportcontent/space-admin');
        $I->see('Some bad post by regular space member');

        $I->expect('Notification for Space Owner but not for Global Admin');

        // Expect Notification for Space Owner
        $I->seeRecord(Notification::class, ['class' => NewReportAdmin::class, 'user_id' => 2]);

        // Expect No Notification for Global Admin
        $I->dontSeeRecord(Notification::class, ['class' => NewReportAdmin::class, 'user_id' => 1]);
    }

    public function testReportAgainstSpaceAdmin(FunctionalTester $I)
    {
        $space2 = Space::findOne(['id' => 2]);

        // Post by Space NonAdmin
        $I->amUser1();
        $post = new Post($space2, Content::VISIBILITY_PUBLIC, ['message' => 'Some bad post by regular space member']);
        $post->content->created_by = 2;
        $post->save();

        // Report by Space NonMember
        $I->amUser3(true);
        $report = new ReportContent(['content_id' => $post->content->id, 'created_by' => 4, 'reason' => 1]);
        $report->save();

        // Space Owner
        $I->amUser1(true);
        $I->amOnSpace2('/reportcontent/space-admin');
        $I->dontSee('Some bad post by regular space member');
        $I->expect('Notification for Global Admin but not  Space Owner');

        // Expect No Notification for Space Owner
        $I->dontSeeRecord(Notification::class, ['class' => NewReportAdmin::class, 'user_id' => 2]);

        // Expect Notification for Global Admin
        $I->seeRecord(Notification::class, ['class' => NewReportAdmin::class, 'user_id' => 1]);

        // Report Visible for Global Admins
        $I->amAdmin(true);
        $I->amOnRoute('/reportcontent/admin');
        $I->see('Some bad post by regular space member');
    }

}