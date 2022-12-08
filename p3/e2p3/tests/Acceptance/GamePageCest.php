<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class gamePageCest
{

    public function testGameDetail(AcceptanceTester $I)
    {
        # assume there is a game #1 from seeding since we seed 5 users and 1-3 games for each user
        $I->amOnPage('/game?id=1');

        # Assert the correct title is set on the page
        $I->seeInTitle('Move history for Game ID');
        # And we have the table header
        $I->see('History');

        $moveCount = count($I->grabMultiple('[test=move-link]'));
        $I->comment('Found moves: ' . $moveCount);

        # games are simulated, so assuming this is a simulated game, we have to have at least 26 moves.
        $I->assertGreaterThanOrEqual(26, $moveCount);
    }
}