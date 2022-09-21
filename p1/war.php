<?php

/*
    Resources:
        https://hesweb.dev/files/e2p1-examples/war/
        https://www.php.net/manual/en/control-structures.switch.php
        https://www.w3schools.com/php/func_array_push.asp
        http://www.hackingwithphp.com/5/6/10/randomising-your-array
        https://www.php.net/manual/en/function.array-keys.php
        https://www.w3schools.com/php/func_array.asp
        https://stackoverflow.com/questions/7113231/how-to-iterate-over-an-array-of-arrays
        https://www.alt-codes.net/suit-cards.php
*/

# creates a deck of 52 cards with numeric values and unicode symbol for suit but delimited by space
function createDeckOfCards()
{
    $suits = array("♥", "♦", "♧", "♤"); #H=Hearts D=Diamonds C=Clubs S=Spades
    $cards = array();

    foreach ($suits as &$suit) {
        for ($i = 2; $i < 15; ++$i) {
            array_push($cards, "$i $suit");
        }
    }

    return $cards;
}

# creates a randomized dec
function getShuffledDeckOfCards()
{
    $deck = createDeckOfCards();
    shuffle($deck);
    return $deck;
}

# translates numeric values for to face card value for display
function getCardDisplay($card)
{
    if ($card <= 10) {
        return $card;
    } # 2-10 don't change

    switch ($card) {
        case 11:
            return "J"; # Jack
        case 12:
            return "Q"; # Queen
        case 13:
            return "K"; # King
        case 14:
            # Note:  Aces are always higher than Kings in this game
            # https://www.hellaentertainment.com/blog/card-games/war/
            return "A"; # Ace
    }
}

# setup a randomized deck of 52 cards
$deck = getShuffledDeckOfCards();
$card_count = (int) count($deck);

# Each player starts with half the deck (26 cards), shuffled in a random order.
$player1_cards  = array_slice($deck, 0, $card_count / 2);
$player2_cards  = array_slice($deck, $card_count / 2);

# Initalize our variables 
$round = 0;
$player1_card = "";
$player2_card = "";
$winner = "";
$results = array(); # this will be an array of arrays that will used to output in view

# Let's play war!
while (sizeof($player1_cards) > 0 && sizeof($player2_cards) > 0) {

    # Use space delimiter to break out value and suit into an array
    # Numeric / First element is used for easy comparisons to determine winner
    $player1_card_value = explode(" ", $player1_cards[0]);
    $player2_card_value = explode(" ", $player2_cards[0]);

    # Whoevers card is highest wins that round and keeps both cards.
    if ($player1_card_value[0] > $player2_card_value[0]) {
        $winner = "Player 1";
        # take card from other player and put at end of deck
        $card = array_shift($player2_cards);
        array_push($player1_cards, $card);

        # move our winning card to end of the deck
        array_push($player1_cards, $player1_cards[0]);
        array_shift($player1_cards);
    } else if ($player2_card_value[0] > $player1_card_value[0]) {
        $winner = "Player 2";
        # take card from other player and put at end of deck
        $card = array_shift($player1_cards);
        array_push($player2_cards, $card);

        # move our winning card to end of the deck
        array_push($player2_cards, $player2_cards[0]);
        array_shift($player2_cards);
    } else {
        $winner = "Tie";
        #  it’s a tie and those cards are discarded.
        array_shift($player1_cards);
        array_shift($player2_cards);
    }

    # save output for display by the view
    $result = array(
        "Round" => $round,
        "Player 1 card" => getCardDisplay($player1_card_value[0]) . " " . $player1_card_value[1],
        "Player 2 card" => getCardDisplay($player2_card_value[0]) . " " . $player2_card_value[1],
        "Winner" => $winner,
        "Player 1 cards left" => (int) count($player1_cards),
        "Player 2 cards left" => (int) count($player2_cards)
    );

    array_push($results, $result);

    $round++;
}

# There is an edge case where the last round is a tie.
# e.g. tie isn't an acceptiable outcome for the game but is for a round.
# For that reason, we can't simply use the last $winner value in the view 

if ($winner == "Tie") {
    if (count($player2_cards) == 0) {
        $winner = "Player 1";
    } else if (count($player1_cards) == 0) {
        $winner = "Player 2";
    }
}


# display the results in the view!
require 'war-view.php';
