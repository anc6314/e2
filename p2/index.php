<?php
require("Deck.php"); # Note: requires php 8.1 or higher for enum support

session_start();

# Is this the first round?
if (!isset($_SESSION['round'])) {
    # Yes, so let's setup our default vars

    $results = array(); # this will be an array of arrays that will used to output in view
    $wins       = 0;
    $ties       = 0;
    $losses     = 0;
    $round      = 1;
    $winner     = "";
    $winnerGame = "";

    $_SESSION['round']              = $round;
    $_SESSION['totalWins']          = $wins;
    $_SESSION['totalTies']          = $ties;
    $_SESSION['totalLoses']         = $losses;
    $_SESSION['results']            = $results;
    $_SESSION['winner']             = $winner;
    $_SESSION['winnerGame']         = $winnerGame;

    $showInstructions = true;

    # now let's setup the game of war!
    $deck = new Deck(true); # get a shuffled deck of cards
    $card_count = count($deck->cards);

    # Each player starts with half the deck (26 cards)
    while ($deck->cards) {
        $player1_cards[]  = array_shift($deck->cards); # Human player
        $computer_cards[] = array_shift($deck->cards);
    }

    # save our card data for the game
    $_SESSION['player1_cards']  = $player1_cards;
    $_SESSION['computer_cards'] = $computer_cards;
} else {
    # Nope, load the history data
    $results        = $_SESSION['results'];
    $wins           = $_SESSION['totalWins'];
    $ties           = $_SESSION['totalTies'];
    $losses         = $_SESSION['totalLoses'];
    $round          = $_SESSION['round'];
    $winner         = $_SESSION['winner'];
    $winnerGame     = $_SESSION['winnerGame'];
}

require 'index-view.php';