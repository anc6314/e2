<?php

namespace App\Controllers;

use App\Controllers\Deck; # Note: requires php 8.1 or higher for enum support

class AppController extends Controller
{

    public function index()
    {
        $name  = $this->app->sessionGet('name');
        $stats = $this->app->sessionGet('stats');
        $games = $this->app->sessionGet('games');

        $users = $this->app->db()->all('users');

        $data = [
            'name'             =>  $name,
            'users'            =>  $users,
            'stats'            =>  $stats,
            'games'            =>  $games,
        ];

        return $this->app->view('index', $data);
    }

    public function play()
    {
        $winnerClass        = "";
        $round              = $this->app->sessionGet('round');

        # Is this the first round?
        if (is_null($round)) {
            # Yes, so let's setup our default vars

            $results = array(); # this will be an array of arrays that will used to output in view
            $wins               = 0;
            $ties               = 0;
            $losses             = 0;
            $round              = 1;
            $winner             = "";
            $winnerGame         = "";

            $losses         = 0;
            $winpercent     = 0;
            $losspercent    = 0;
            $tiepercent     = 0;

            $this->app->sessionSet('round', $round);
            $this->app->sessionSet('totalWins', $wins);
            $this->app->sessionSet('totalTies', $ties);
            $this->app->sessionSet('totalLoses', $losses);
            $this->app->sessionSet('results', $results);
            $this->app->sessionSet('winner', $winner);
            $this->app->sessionSet('winnerGame', $winnerGame);

            # now let's setup the game of war!
            $deck = new Deck(true); # get a shuffled deck of cards

            # Each player starts with half the deck (26 cards)
            while ($deck->cards) {
                $player1_cards[]  = array_shift($deck->cards); # Human player
                $computer_cards[] = array_shift($deck->cards);
            }

            # save our card data for the game
            $this->app->sessionSet('player1_cards',  $player1_cards);
            $this->app->sessionSet('computer_cards', $computer_cards);
        } else {
            # Nope, load the history data
            $results        = $this->app->sessionGet('results');
            $wins           = $this->app->sessionGet('totalWins');
            $ties           = $this->app->sessionGet('totalTies');
            $losses         = $this->app->sessionGet('totalLoses');
            $round          = $this->app->sessionGet('round');
            $winner         = $this->app->sessionGet('winner');
            $winnerGame     = $this->app->sessionGet('winnerGame');

            $player1_cards  = $this->app->sessionGet('player1_cards');
            $computer_cards = $this->app->sessionGet('computer_cards');

            # calc stats
            $losses         = $round - ($wins + $ties);
            $winpercent     = round(($wins / $round) * 100, 2);
            $losspercent    = round(($losses / $round) * 100, 2);
            $tiepercent     = round(($ties / $round) * 100, 2);

            if ($winner == "You") {
                $winnerClass =  "success";
            } else if ($winner == "Computer") {
                $winnerClass =  "danger";
            } else if ($winner == "Tie") {
                $winnerClass =  "warning";
            }
        }

        $player1CardPath    = $player1_cards[0]->getImagePath();
        $computerCardPath   = $computer_cards[0]->getImagePath();

        $data = [
            'round'             =>  $round,
            'winner'            =>  $winner,
            'winnerGame'        =>  $winnerGame,
            'winnerClass'       =>  $winnerClass,
            'player1_cards'     =>  '',
            'computer_cards'    =>  '',
            'wins'              =>  $wins,
            'ties'              =>  $ties,
            'losses'            =>  $losses,
            'winpercent'        => $winpercent,
            'losspercent'       => $losspercent,
            'tiepercent'        => $tiepercent,
            'player1CardPath'   => $player1CardPath,
            'computerCardPath'  => $computerCardPath,
            'results'           => $results,

        ];

        return $this->app->view('play', $data);
    }

    public function process()
    {
        $wins               = 0;
        $ties               = 0;
        $losses             = 0;
        $round              = 1;
        $winner             = "";
        $winnerGame         = "";
        $winnerClass        = "";
        $player1CardPath    = "";
        $computerCardPath   = "";

        $choice = $this->app->input('choice', '');

        if ($choice == 'reset') {
            session_destroy();
        } else {
            $round          = $this->app->sessionGet('round');
            $results        = $this->app->sessionGet('results');
            $winner         = $this->app->sessionGet('winner');
            $player1_cards  = $this->app->sessionGet('player1_cards');
            $computer_cards = $this->app->sessionGet('computer_cards');
            $wins           = $this->app->sessionGet('totalWins');
            $ties           = $this->app->sessionGet('totalTies');
            $losses         = $this->app->sessionGet('totalLoses');

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
        }

        # calc stats
        $winpercent     = round(($wins / $round) * 100, 2);
        $losspercent    = round(($losses / $round) * 100, 2);
        $tiepercent     = round(($ties / $round) * 100, 2);

        # save data to session
        $this->app->sessionSet('round',           $round);
        $this->app->sessionSet('results',         $results);
        $this->app->sessionSet('winner',          $winner);
        $this->app->sessionSet('winnerClass',     $winnerClass);
        $this->app->sessionSet('winnerGame',      $winnerGame); # camel case used for vars
        $this->app->sessionSet('player1_cards',   $player1_cards); #underscore used because this is array of objects
        $this->app->sessionSet('computer_cards',  $computer_cards); #underscore used because this is array of objects
        $this->app->sessionSet('totalWins',       $wins); # camel case used for vars
        $this->app->sessionSet('totalTies',       $ties);
        $this->app->sessionSet('totalLoses',      $losses);


        $data = [
            'round'             =>  $round,
            'winner'            =>  $winner,
            'winnerGame'        =>  $winnerGame,
            'winnerClass'       =>  $winnerClass,
            'player1_cards'     =>  '',
            'computer_cards'    =>  '',
            'wins'              =>  $wins,
            'ties'              =>  $ties,
            'losses'            =>  $losses,
            'winpercent'        => $winpercent,
            'losspercent'       => $losspercent,
            'tiepercent'        => $tiepercent,
            'player1CardPath'   => $player1CardPath,
            'computerCardPath'  => $computerCardPath,
            'results'           => $results,

        ];

        $this->app->redirect('/play', $data);
    }

    public function register()
    {
        # pull data in from the form and see if there is an existing user . . .
        $name = $this->app->input('name', '');
        $users = $this->app->db()->findByColumn('users', 'name', '=', $name);

        if (!empty($users)) {
            $user = $users[0];
            $user_id = $user['id'];

            # now pull in the game history to the session so it can display when/if they return
            $games = $this->app->db()->findByColumn('games', 'user_id', '=', $user_id);
            $this->app->sessionSet('games', $games);

            # Calc some basic stats

            # https://stackoverflow.com/questions/37458399/count-all-rows-by-status

            $sql = 'SELECT status, count(*) FROM games WHERE user_id = :user_id GROUP BY status';
            $data = ['user_id' => $user_id];

            $executed = $this->app->db()->run($sql, $data);
            $stats = $executed->fetchAll();
            $this->app->sessionSet('stats', $stats);
        } else {
            # if not, create the user
            $data = ['name' => $name];
            $user_id = $this->app->db()->insert('users', $data);
        }

        # set the user/player name and ID in the session so they can play!
        $this->app->sessionSet('name',    $name);
        $this->app->sessionSet('user_id', $user_id);

        # now send them to the game!
        $this->app->redirect('/play');
    }
}