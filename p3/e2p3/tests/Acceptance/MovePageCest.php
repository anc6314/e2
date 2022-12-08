<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class movePageCest
{

    public function testMoveDetail(AcceptanceTester $I)
    {
        # assume there is a move #1 from seeding since we seed 5 users and 1-3 games for each user, and simulate the full game moves
        # note that the user sees them as rounds but they are moves in the DB
        $I->amOnPage('/round?id=1');

        # Assert the correct title is set on the page
        $I->seeInTitle('Details for Game');

        # Check for card images
        $I->seeElement('[test=player-card]');
        $I->seeElement('[test=computer-card]');

        # And we have the table header
        $I->see('Results');

        $moveCount = count($I->grabMultiple('[test=move-number]'));
        $I->comment('Found moves: ' . $moveCount);

        # The detail page should only ever have one move
        $I->assertEquals(1, $moveCount);
    }
}