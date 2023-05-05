<?php
namespace reportcontent;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \AcceptanceTester
{
    use _generated\AcceptanceTesterActions;

    public function setProfanityFilter()
    {
        $this->amAdmin();
        $this->wait(2);
        $this->amOnRoute(['/reportcontent/admin/configuration']);
        $this->see('Profanity Filter');
        $this->fillField('#configuration-profanityfilterlist', 'ass');
        $this->checkOption('#configuration-blockcontributions');
        $this->click('Save');
        $this->waitForText('Saved', 5);
    }
}
