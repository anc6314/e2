<?php
require("Deck.php"); # Note: requires php 8.1 or higher for enum support

$deck = new Deck(true); # get a shuffled deck of cards
$card_count = count($deck->cards);

# Each player starts with half the deck (26 cards)
while ($deck->cards) {
    $player1_cards[] = array_shift($deck->cards);
    $player2_cards[] = array_shift($deck->cards);
}

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
    $player1_card = $player1_cards[0];
    $player2_card = $player2_cards[0];

    # Whoevers card is highest wins that round and keeps both cards.
    if ($player1_card->value > $player2_card->value) {
        $winner = "Player 1";
        # take card from other player and put at end of deck
        $card = array_shift($player2_cards);
        array_push($player1_cards, $card);

        # move our winning card to end of the deck
        array_push($player1_cards, $player1_cards[0]);
        array_shift($player1_cards);
    } else if ($player2_card->value > $player1_card->value) {
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
        "Player 1 card" => $player1_card->getName(),
        "Player 2 card" => $player2_card->getName(),
        "Winner" => $winner,
        "Player 1 cards left" => (int) count($player1_cards),
        "Player 2 cards left" => (int) count($player2_cards)
    );

    array_push($results, $result);

    $round++;
}

# There is an edge case where the last round is a tie (and one user is out of cards after)
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
require 'index-view.php';