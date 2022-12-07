<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class IndexPageCest
{
    public function testBlankName(AcceptanceTester $I)
    {
        # Index / home page
        $I->amOnPage('/');

        # Assert the correct title is set on the page
        $I->seeInTitle('War Card Game - Home');

        # Assert the existence of certain text on the page
        $I->see('Would you like to play a game?');

        # test for error message when name is blank
        $I->click('[test=register-button]');
        $I->see('The value for name can not be blank');
    }

    public function testRegistration(AcceptanceTester $I)
    {
        # Index / home page
        $I->amOnPage('/');

        # Assert the correct title is set on the page
        $I->seeInTitle('War Card Game - Home');

        # Assert the existence of certain text on the page
        $I->see('Would you like to play a game?');

        # register a new user
        $I->fillField('[test=register-name]', 'Bob Smith');
        $I->click('[test=register-button]');

        # we have been redirected to play the game!
        $I->seeInCurrentUrl('/play');
    }
}