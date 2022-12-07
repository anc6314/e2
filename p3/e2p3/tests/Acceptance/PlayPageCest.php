<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use App\Controllers\Card; # Note: requires php 8.1 or higher for enum support

class PlayPageCest
{
    public function testPlay(AcceptanceTester $I)
    {
        # Need to register before we can play . . .
        $I->amOnPage('/');
        $I->fillField('[test=register-name]', 'Bob Smith');
        $I->click('[test=register-button]');

        # Are we now playing?
        $I->seeInTitle('Playing war!');
        $I->see('Current Round: 1');
        $I->see('cards remaining.');

        # take the src URL of the image on the page and get just the base card (w/o suit)
        $player_card = str_replace('.png', '', explode('_', $I->grabAttributeFrom('[test=player-card-image]', 'src'))[1]);
        $computer_card = str_replace('.png', '', explode('_', $I->grabAttributeFrom('[test=computer-card-image]', 'src'))[1]);

        # files are named ace, queen, etc but need single letters
        # then we use the static method to convert the letter to numeric value
        if (!is_numeric($player_card)) {
            $player_card = strtoupper(substr($player_card, 0, 1));
            $player_card = Card::getValueFromDisplay($player_card);
        }

        if (!is_numeric($computer_card)) {
            $computer_card = strtoupper(substr($computer_card, 0, 1));
            $computer_card = Card::getValueFromDisplay($computer_card);
        }

        # cast to numeric so we can see who won!
        $player_card   = (int) $player_card;
        $computer_card = (int) $computer_card;

        $I->comment('Player card: ' . $player_card);
        $I->comment('Computer card: ' . $computer_card);

        # now let's play a round
        # for simplicity sake we are going to use the keep option
        # this is mainly because the new/random card isn't shown on the
        # screen so no way to really know before hand who would win
        $I->fillField('[test=shuffle-radio]', 'keep');
        $I->click('[test=play-button]');

        # is the winner correct?
        if ($player_card == $computer_card) {
            $I->seeElement('[test=tie-output]');
        } else if ($player_card > $computer_card) {
            $I->seeElement('[test=won-output]');
        } else {
            $I->seeElement('[test=lost-output]');
        }

        # confirm that we have some details about the round we just played
        $I->see('History');
    }
}