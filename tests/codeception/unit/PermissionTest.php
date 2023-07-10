<?php

namespace tests\codeception\unit\modules\space;

use humhub\modules\comment\models\Comment;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\helpers\Permission;
use humhub\modules\space\models\Space;
use tests\codeception\_support\HumHubDbTestCase;

class PermissionTest extends HumHubDbTestCase
{
    public function testReportContent()
    {
        $user1 = $this->becomeUser('user1');

        // User cannot report own Content
        $post = Post::findOne(3);
        $this->assertEquals($user1->id, $post->content->created_by);
        $this->assertFalse(Permission::canReportContent($post));

        // User can report Content of another user
        $post = Post::findOne(1);
        $this->assertNotEquals($user1->id, $post->content->created_by);
        $this->assertTrue(Permission::canReportContent($post));
    }

    public function testReportComment()
    {
        $user1 = $this->becomeUser('user1');

        $comment = new Comment([
            'message' => 'Comment 1',
            'object_model' => Post::class,
            'object_id' => 1
        ]);
        $comment->save();

        // User cannot report own Comment
        $comment = Comment::findOne(1);
        $this->assertEquals($user1->id, $comment->created_by);
        $this->assertFalse(Permission::canReportComment($comment));

        // User can report Comment of another user
        $user2 = $this->becomeUser('user2');
        $this->assertNotEquals($user2->id, $comment->created_by);
        $this->assertTrue(Permission::canReportComment($comment));
    }

    public function testManageSpaceReports()
    {
        $user1 = $this->becomeUser('user1');

        $space1 = Space::findOne(['id' => 1]);
        $this->assertFalse($space1->isMember());

        // Not member cannot manage reports
        $this->assertFalse(Permission::canManageReports($space1));

        // Simple member cannot manage reports
        $this->assertTrue($space1->addMember($user1->id));
        $this->assertFalse(Permission::canManageReports($space1));

        // Moderator can manage reports
        $space1->removeMember($user1->id);
        $this->assertFalse(Permission::canManageReports($space1));
        $this->assertTrue($space1->addMember($user1->id, 1, true, Space::USERGROUP_MODERATOR));
        $this->assertTrue(Permission::canManageReports($space1));

        // Space admin can manage reports
        $space1->removeMember($user1->id);
        $this->assertFalse(Permission::canManageReports($space1));
        $space1->addMember($user1->id);
        $space1->setAdmin($user1->id);
        $this->assertTrue(Permission::canManageReports($space1));
    }

    public function testManageUserReports()
    {
        $user1 = $this->becomeUser('user1');

        // User cannot manage reports of content from own profile
        $this->assertFalse(Permission::canManageReports($user1));

        // Only system admin can manage reports from user profile pages
        $this->becomeUser('admin');
        $this->assertTrue(Permission::canManageReports($user1));
    }
}
