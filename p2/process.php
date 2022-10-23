<?php
require("Deck.php"); # Note: requires php 8.1 or higher for enum support

session_start();

# get form var
$choice = $_POST['choice'];

if ($choice == 'reset') {
    session_destroy();
} else {
    # Set vars from session
    $round          = $_SESSION['round'];
    $results        = $_SESSION['results'];
    $winner         = $_SESSION['winner'];
    $player1_cards  = $_SESSION['player1_cards'];
    $computer_cards = $_SESSION['computer_cards'];
    $wins           = $_SESSION['totalWins'];
    $ties           = $_SESSION['totalTies'];

    # calc stats
    $losses = ($round - 1) - ($wins + $ties);

    # --------------------------
    if ($choice == 'random') {
        shuffle($player1_cards);
    }


    $player1_card = $player1_cards[0];
    $computer_card = $computer_cards[0];

    # Card with highest value wins that round and keeps both cards.
    if ($player1_card->value > $computer_card->value) {
        $winner = "You";
        $wins++;

        # take card from other player and put at end of deck
        $card = array_shift($computer_cards);
        array_push($player1_cards, $card);

        # move our winning card to end of the deck
        array_push($player1_cards, $player1_cards[0]);
        array_shift($player1_cards);
    } elseif ($computer_card->value > $player1_card->value) {
        $winner = "Computer";
        $losses++;
        # take card from other player and put at end of deck
        $card = array_shift($player1_cards);
        array_push($computer_cards, $card);

        # move our winning card to end of the deck
        array_push($computer_cards, $computer_cards[0]);
        array_shift($computer_cards);
    } else {
        $winner = "Tie";
        $ties++;
        #  it’s a tie and those cards are discarded.
        array_shift($player1_cards);
        array_shift($computer_cards);
    }

    if ($winner == "You") {
        $winnerClass =  "success";
    } else if ($winner == "Computer") {
        $winnerClass =  "danger";
    } else if ($winner == "Tie") {
        $winnerClass =  "warning";
    }

    # save output for display by the view
    $result = array(
        "Round"                 => $round,
        "Player 1 card"         => $player1_card->getName(),
        "Player 1 card class"   => $player1_card->getClassName(),
        "Computer card"         => $computer_card->getName(),
        "Computer card class"   => $computer_card->getClassName(),
        "Winner"                => $winner,
        "Winner class"          => $winnerClass,
        "Player 1 cards left"   => (int) count($player1_cards),
        "Computer cards left"   => (int) count($computer_cards),
        "Choice"                => $choice
    );

    array_unshift($results, $result); # the history should be kept with the latest round on top - who like scrolling!
    $round++;

    # There is an edge case where the last round is a tie (and one user is out of cards after)
    # e.g. tie isn't an acceptiable outcome for the game but is for a round.
    # For that reason, we can't simply use the last $winner value in the view 

    if (count($computer_cards) == 0 || count($player1_cards) == 0) {
        if (count($computer_cards) == 0 && count($player1_cards) == 0) {
            $winnerGame = "Tie";
        } else if (count($computer_cards) == 0) {
            $winnerGame = "You";
        } else if (count($player1_cards) == 0) {
            $winnerGame = "Computer";
        }
    }

    # save data to session
    $_SESSION['round']          = $round;
    $_SESSION['results']        = $results;
    $_SESSION['winner']         = $winner;
    $_SESSION['winnerClass']    = $winnerClass;
    $_SESSION['winnerGame']     = $winnerGame; # camel case used for vars
    $_SESSION['player1_cards']  = $player1_cards; #underscore used because this is array of objects
    $_SESSION['computer_cards'] = $computer_cards; #underscore used because this is array of objects
    $_SESSION['totalWins']      = $wins; # camel case used for vars
    $_SESSION['totalTies']      = $ties;
    $_SESSION['totalLoses']     = $losses;
}

header('Location: index.php');