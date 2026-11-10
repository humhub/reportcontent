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
        $commentSection = $postEntry . ' .comment-container';
        $commentEntry = $commentSection . ' .single-comment';
        $I->click('Comment', $postEntry);
        $I->wait(1);
        $I->fillField($commentSection . ' .ProseMirror[contenteditable="true"]', 'Bad comment');
        $I->click('.btn-comment-submit', $commentSection);
        $I->waitForElementVisible($commentEntry);
        $I->see('Bad comment', $commentEntry . ' .comment-message');

        $I->amUser2(true);
        $I->amOnSpace2();

        $I->waitForElementVisible($commentEntry);
        $I->wait(1);
        // The comment menu toggle is hidden until the comment is hovered
        $I->moveMouseOver($commentEntry);
        $I->wait(1);
        $I->click('.preferences .dropdown-toggle', $commentEntry);
        $I->wait(1);
        $I->click('Report', $commentEntry);
        $I->waitForElementVisible('#reportcontent-reason');
        $I->checkOption('//input[@name="ReportContent[reason]" and @value="2"]');
        $I->click('Send', '#globalModal');
        $I->waitForText('Content successfully reported.');

        $I->amAdmin(true);
        $I->amOnRoute(['/reportcontent/admin']);
        $I->waitForText('Bad comment');
    }

    public function testCommentMatchWholeWords(AcceptanceTester $I)
    {
        $I->setProfanityFilter();

        $I->amUser1(true);
        $I->amOnSpace2();
        $I->waitForText('Admin Space 2 Post Public');
        $postEntry = '.wall_humhubmodulespostmodelsPost_12';
        $commentSection = $postEntry . ' .comment-container';
        $commentEntry = $commentSection . ' .single-comment';
        $I->click('Comment', $postEntry);
        $I->wait(1);
        $I->fillField($commentSection . ' .ProseMirror[contenteditable="true"]', 'Some ass!');
        $I->click('.btn-comment-submit', $commentSection);
        $I->waitForText('Your contribution does not comply with our community guidelines and can therefore not be published.', 5);

        $I->fillField($commentSection . ' .ProseMirror[contenteditable="true"]', 'Some bass!');
        $I->click('.btn-comment-submit', $commentSection);
        $I->waitForElementVisible($commentEntry);
        $I->see('Some bass!', $commentEntry . ' .comment-message');
    }
}
