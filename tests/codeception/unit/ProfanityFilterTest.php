<?php

namespace tests\codeception\unit\modules\space;

use humhub\modules\comment\models\Comment;
use humhub\modules\content\models\Content;
use humhub\modules\post\models\Post;
use humhub\modules\reportcontent\models\Configuration;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\reportcontent\Module;
use humhub\modules\space\models\Space;
use tests\codeception\_support\HumHubDbTestCase;
use Yii;

class ProfanityFilterTest extends HumHubDbTestCase
{
    // testReportPost

    // TestRepotComment

    // testBlockComment

    public function testBlockPost()
    {
        $this->becomeUser('user2');

        $space2 = Space::findOne(['id' => 2]);

        $post = new Post($space2, Content::VISIBILITY_PUBLIC, ['message' => 'Some bad post by regular space member']);
        $post->content->created_by = 2;
        $this->assertTrue($post->save());

        $this->getConfiguration()->profanityFilterList = 'bad,satan';
        $this->getConfiguration()->blockContributions = true;
        $this->getConfiguration()->save();

        $post = new Post($space2, Content::VISIBILITY_PUBLIC, ['message' => 'Some bad post by regular space member']);
        $post->content->created_by = 2;
        $this->assertFalse($post->save());

        $post = new Post($space2, Content::VISIBILITY_PUBLIC, ['message' => 'Some post by regular space member']);
        $post->content->created_by = 2;
        $this->assertTrue($post->save());
    }

    public function testBlockComment()
    {
        $this->becomeUser('user2');

        $post = Post::findOne(['id' => 1]);

        $comment = new Comment(['object_model' => $post::class, 'object_id' => $post->id, 'message' => 'Some bad comment by regular space member']);
        $this->assertTrue($comment->save());

        $this->getConfiguration()->profanityFilterList = 'bad,satan';
        $this->getConfiguration()->blockContributions = true;
        $this->getConfiguration()->save();

        $comment = new Comment(['object_model' => $post::class, 'object_id' => $post->id, 'message' => 'Some bad comment by regular space member']);
        $this->assertFalse($comment->save());
    }


    public function testReportPost()
    {
        $this->becomeUser('user2');

        $space2 = Space::findOne(['id' => 2]);

        $post = new Post($space2, Content::VISIBILITY_PUBLIC, ['message' => 'Some bad post by regular space member']);
        $post->content->created_by = 2;
        $this->assertTrue($post->save());
        $this->assertNull(ReportContent::findOne(['content_id' => $post->content->id]));

        $this->getConfiguration()->profanityFilterList = 'bad,satan';
        $this->getConfiguration()->blockContributions = false;
        $this->getConfiguration()->save();

        $post = new Post($space2, Content::VISIBILITY_PUBLIC, ['message' => 'Some bad post by regular space member']);
        $post->content->created_by = 2;
        $this->assertTrue($post->save());
        $this->assertNotNull(ReportContent::findOne(['content_id' => $post->content->id]));
    }

    public function testReportComment()
    {
        $this->becomeUser('user2');

        $post = Post::findOne(['id' => 1]);

        $comment = new Comment(['object_model' => $post::class, 'object_id' => $post->id, 'message' => 'Some bad comment by regular space member']);
        $this->assertTrue($comment->save());
        $this->assertNull(ReportContent::findOne(['comment_id' => $comment->id]));

        $this->getConfiguration()->profanityFilterList = 'bad,satan';
        $this->getConfiguration()->blockContributions = false;
        $this->getConfiguration()->save();

        $comment = new Comment(['object_model' => $post::class, 'object_id' => $post->id, 'message' => 'Some bad comment by regular space member']);
        $this->assertTrue($comment->save());
        $this->assertNotNull(ReportContent::findOne(['comment_id' => $comment->id]));
    }

    private function getConfiguration(): Configuration
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('reportcontent');
        return $module->getConfiguration();

    }
}
