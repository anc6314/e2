<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class PlayerPageCest
{

    public function testPlayerDetail(AcceptanceTester $I)
    {
        # assume there is a player #1 from seeding
        $I->amOnPage('/player?id=1');

        # Assert the correct title is set on the page
        $I->seeInTitle('Player History for ');
        # And we have the table header
        $I->see('Games Played');

        $gameCount = count($I->grabMultiple('[test=game-link]'));
        #$I->assertGreaterThanOrEqual(3, $gameCount);
    }
}