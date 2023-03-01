<?php



use reportcontent\AcceptanceTester;

class ReportCommentCest
{

    public function testReportAndDeleteSimplePost(AcceptanceTester $I)
    {

        $I->amUser1();
        $I->amOnSpace2();
        $I->waitForText('Admin Space 2 Post Public');
        $postEntry = '.wall_humhubmodulespostmodelsPost_12';
        $commentSection  = $postEntry.' .comment-container';
        $I->click('Comment', $postEntry);
        $I->wait(1);
        $I->fillField($commentSection.' .humhub-ui-richtext[contenteditable]', 'Bad comment');
        $I->click('.btn-comment-submit', $commentSection);
        $I->waitForElementVisible('#comment-message-1');
        $I->see('Bad comment','#comment-message-1');

        $I->amUser2(true);
        $I->amOnSpace2();

        $I->waitForElementVisible('#comment-message-1');
        $I->wait(1);
        $I->moveMouseOver('#comment-message-1');
        $I->wait(1);
        $I->click('.fa.fa-angle-down', '#comment_1');
        $I->wait(1);
        $I->click('Report', '#comment_1');
        $I->waitForElementVisible('#reportcontent-reason');
        $I->checkOption('//input[@name="ReportContent[reason]" and @value="2"]');
        $I->click('Send', '#globalModal');
        $I->waitForText('Content successfully reported.');

        $I->amAdmin(true);
        $I->amOnRoute(['/reportcontent/admin']);
        $I->waitForText('Bad comment');
    }
}
