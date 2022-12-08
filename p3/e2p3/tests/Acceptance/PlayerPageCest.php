<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class PlayerPageCest
{

    public function testPlayerDetail(AcceptanceTester $I)
    {
        # assume there is a player #1 from seeding since we seed 5 users
        $I->amOnPage('/player?id=1');

        # Assert the correct title is set on the page
        $I->seeInTitle('Player History for ');
        # And we have the table header
        $I->see('Games Played');

        $gameCount = count($I->grabMultiple('[test=game-link]'));
        $I->comment('Found games: ' . $gameCount);

        # We seed 1 - 3 games randomly, so we should have at least one
        $I->assertGreaterThanOrEqual(1, $gameCount);
    }
}