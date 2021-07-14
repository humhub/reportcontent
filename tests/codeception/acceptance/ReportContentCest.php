<?php

namespace reportcontent\acceptance;


use reportcontent\AcceptanceTester;

class ReportContentCest
{

    public function testReportAndDeleteSimplePost(AcceptanceTester $I)
    {
        $I->amUser1();
        $I->wantToTest('the report of a simple member post');
        $I->amGoingTo('add a new post as member');
        $I->amOnSpace3();
        $I->createPost('Some bad words!');

        $I->amUser2(true);
        $I->amOnSpace3();
        $I->amGoingTo('report the new post as another member');
        $I->waitForElementVisible('.wall-entry');
        $I->jsClick('.wall-entry .dropdown-toggle');
        $I->wait(1);
        $I->click('Report post');
        $I->waitForElementVisible('#reportreasonform-reason');
        $I->jsClick("#reportreasonform-reason [value=2]");
        $I->click('#submitReport');
        $I->wait(5);

        $I->amGoingTo('login as admin');
        $I->amAdmin(true);
        $I->wait(4);
        $I->expectTo('see a report notification');
        $I->seeInNotifications('has reported post');
        $I->seeInNotifications('Some bad words!');

        $I->wantToTest('the deletion of the report post');
        $I->amOnSpace3();
        $I->jsClick('.controls-header .fa-cog');
        $I->wait(2);
        $I->click('Reported posts');

        $I->expectTo('see a report notification');

        $I->waitForText('Manage reported posts');
        $I->seeElement('a[data-original-title="Review"]');

        $I->amGoingTo('delete the post after review');
        $I->jsClick('a[data-original-title="Review"]');
        $I->waitForText('Back to stream');
        $I->jsClick('.dropdown-toggle[aria-label="Toggle stream entry menu"]');
        $I->click('Delete');
        $I->waitForText('Confirm post deletion');
        $I->jsClick('button[data-modal-confirm]');

        $I->wait(5);

        $I->expect('not to see the deleted post');
        $I->see('This space is still empty!');

        $I->expect('not to see the reported post');
        $I->amOnSpace3();
        $I->wait(5);
        $I->dontSee('Some bad words!');
    }

    public function testReportAndApproveSimplePost(AcceptanceTester $I)
    {
        $I->amUser1();
        $I->wantToTest('the report of a simple member post');
        $I->amGoingTo('add a new post as member');
        $I->amOnSpace3();
        $I->createPost('Some bad words!');

        $I->amUser2(true);
        $I->amOnSpace3();
        $I->amGoingTo('report the new post as another member');
        $I->waitForElementVisible('.wall-entry');
        $I->jsClick('.wall-entry .dropdown-toggle');
        $I->wait(1);
        $I->click('Report post');
        $I->waitForElementVisible('#reportreasonform-reason');
        $I->jsClick("#reportreasonform-reason [value=2]");
        $I->click('#submitReport');
        $I->wait(5);

        $I->amGoingTo('login as admin');
        $I->amAdmin(true);
        $I->wait(4);
        $I->expectTo('see a report notification');
        $I->seeInNotifications('has reported post');
        $I->seeInNotifications('Some bad words!');

        $I->wantToTest('the deletion of the report post');
        $I->amOnSpace3();
        $I->jsClick('.controls-header .fa-cog');
        $I->wait(2);
        $I->click('Reported posts');

        $I->expectTo('see a report notification');
        $I->waitForText('Manage reported posts');
        $I->seeElement('a[data-original-title="Approve"]');

        $I->amGoingTo('approve the post in report view');
        $I->jsClick('a[data-original-title="Approve"]');
        $I->wait(2);
        $I->jsClick('.modalConfirm:visible');

        $I->wait(5);

        $I->expect('not to see the report anymore');
        $I->dontSee('Some bad words!');
        $I->see('There are no reported posts.');

        $I->expect('see the approved post in Space stream');
        $I->amOnSpace3();
        $I->wait(5);
        $I->see('Some bad words!');
    }

    public function testReportSpaceAdminPost(AcceptanceTester $I)
    {
        $I->amUser1();
        $I->wantToTest('the report of a space admin post');
        $I->amGoingTo('add a new post as space admin');
        $I->amOnSpace4();
        $I->createPost('Insults!');

        $I->amGoingTo('report the post as space member');
        $I->amUser2(true);
        $I->amOnSpace4();
        $I->wait(5);
        $I->jsClick('.wall-entry .dropdown-toggle');
        $I->wait(1);
        $I->click('Report post');
        $I->waitForElementVisible('#reportreasonform-reason');
        $I->jsClick("#reportreasonform-reason [value=2]");
        $I->click('#submitReport');
        $I->wait(1);

        $I->wantToTest('that the space admin does not get an notification');
        $I->amUser1(true);
        $I->expect('not to see a report notification');
        $I->dontSeeInNotifications('has reported post');

        $I->wantToTest('the approval of the reported post');
        $I->amAdmin(true);
        $I->wait(4);
        $I->seeInNotifications('has reported post');
        $I->seeInNotifications('Insults!');

        $I->amOnSpace4();
        $I->jsClick('.controls-header .fa-cog');
        $I->wait(2);
        $I->click('Reported posts');

        $I->dontSeeElement('a[data-original-title="Approve"]');
        $I->dontSeeElement('a[data-original-title="Review"]');

        $I->amOnRoute(['/reportcontent/admin']);

        $I->seeElement('a[data-original-title="Approve"]');
        $I->seeElement('a[data-original-title="Review"]');

        $I->amGoingTo('approve the reported post');
        $I->jsClick('a[data-original-title="Approve"]');
        $I->wait(2);
        $I->jsClick('.modalConfirm:visible');

        $I->wait(5);

        $I->expect('not to see the report');
        $I->dontSee('Some bad words!');
        $I->see('There are no reported posts.');

        $I->amOnSpace4();
        $I->expect('not to still see the post');
        $I->wait(5);
        $I->see('Insults!');
    }

    public function testReportProfilePost(AcceptanceTester $I)
    {
        $I->amUser1();
        $I->wantToTest('the report of a simple member post');
        $I->amGoingTo('add a new post as member');
        $I->amOnProfile();
        $I->createPost('Some bad words!');

        $I->amUser2(true);
        $I->amOnUser1Profile();
        $I->wait(3);
        $I->see('Some bad words!');

        $I->amGoingTo('report the insulting post');
        $I->waitForElementVisible('.wall-entry');
        $I->jsClick('.wall-entry .dropdown-toggle');
        $I->wait(1);
        $I->click('Report post');
        $I->waitForElementVisible('#reportreasonform-reason');
        $I->jsClick("#reportreasonform-reason [value=2]");
        $I->click('#submitReport');
        $I->wait(10);

        $I->amGoingTo('login as admin');
        $I->amAdmin(true);
        $I->wait(2);
        $I->expectTo('see a report notification');
        $I->seeInNotifications('has reported post');
        $I->seeInNotifications('Some bad words!');

        $I->wantToTest('the deletion of the report post');
        $I->amOnRoute(['/reportcontent/admin']);

        $I->expectTo('see a report notification');
        $I->seeElement('a[data-original-title="Approve"]');
        $I->seeElement('a[data-original-title="Review"]');

        $I->amGoingTo('delete the post after review');
        $I->jsClick('a[data-original-title="Review"]');
        $I->waitForText('Back to stream');
        $I->jsClick('.dropdown-toggle[aria-label="Toggle stream entry menu"]');
        $I->click('Delete');
        $I->waitForText('Confirm post deletion');
        $I->jsClick('button[data-modal-confirm]');

        $I->wait(5);

        $I->expect('not to see the deleted post');
        $I->see('No matches with your selected filters!');

        $I->amOnUser1Profile();
        $I->expect('not to see the reported post');
        $I->wait(5);
        $I->dontSee('Some bad words!');
    }
}