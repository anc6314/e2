<?php
require("Deck.php"); # Note: requires php 8.1 or higher for enum support

session_start();

# Is this the first round?
if (!isset($_SESSION['round'])) {
    # Yes, so let's setup our default vars
    $_SESSION['round']      = 1;
    $_SESSION['totalWins']  = 0;
    $_SESSION['totalTies']  = 0;
    $_SESSION['results']    = array(); # this will be an array of arrays that will used to output in view
    $_SESSION['winner']     = '';

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
    $results = $_SESSION['results'];
    $wins    = $_SESSION['totalWins'];
    $ties    = $_SESSION['totalTies'];
    $losses  = $_SESSION['totalLoses'];
    $round   = $_SESSION['round'];
}

require 'index-view.php';