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
            'name'  =>  $name,
            'users' =>  $users,
            'stats' =>  $stats,
            'games' =>  $games,
        ];

        return $this->app->view('index', $data);
    }

    public function game()
    {
        $game_id = $this->app->param('id');

        if (is_null($game_id)) {
            $this->app->redirect('/'); # send them back to the home page; could add in error message in the future
        }

        $moves = $this->app->db()->findByColumn('moves', 'game_id', '=', $game_id);

        if (empty($moves)) {
            $this->app->redirect('/'); # send them back to the home page; could add in error message in the future
        }

        $data = [
            'game_id' =>  $game_id,
            'moves'   =>  $moves,
        ];

        return $this->app->view('/game', $data);
    }

    public function play()
    {
        $winner_game    = "";
        $winner_class   = "";
        $round          = $this->app->sessionGet('round');
        $user_id        = $this->app->sessionGet('user_id');

        # Is this the first round?
        if (is_null($round)) {
            # Yes, so let's setup our default vars

            $results    = array(); # this will be an array of arrays that will used to output in view
            $wins        = 0;
            $ties        = 0;
            $losses      = 0;
            $round       = 1;
            $winner      = "";

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
            $this->app->sessionSet('winner_game', $winner_game);

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

            # save game to DB
            $data = [
                'user_id'   => $user_id,
                'status'    => 'playing',
                'score'     => 0,
            ];

            $game_id = $this->app->db()->insert('games', $data);
            $this->app->sessionSet('game_id',  $game_id);
        } else {

            # Nope, load the history data
            $results        = $this->app->sessionGet('results'); # // <<< Need to load this from the DB!
            $wins           = $this->app->sessionGet('totalWins');
            $ties           = $this->app->sessionGet('totalTies');
            $losses         = $this->app->sessionGet('totalLoses');
            $round          = $this->app->sessionGet('round');
            $winner         = $this->app->sessionGet('winner');
            $winner_game    = $this->app->sessionGet('winner_game');

            $player1_cards  = $this->app->sessionGet('player1_cards');
            $computer_cards = $this->app->sessionGet('computer_cards');

            # calc stats
            $losses         = $round - ($wins + $ties);
            $winpercent     = round(($wins / $round) * 100, 2);
            $losspercent    = round(($losses / $round) * 100, 2);
            $tiepercent     = round(($ties / $round) * 100, 2);

            if ($winner == "You") {
                $winner_class =  "success";
            } else if ($winner == "Computer") {
                $winner_class =  "danger";
            } else if ($winner == "Tie") {
                $winner_class =  "warning";
            }
        }

        $player1CardPath    = $player1_cards[0]->getImagePath();
        $computerCardPath   = $computer_cards[0]->getImagePath();

        $data = [
            'round'             => $round,
            'winner'            => $winner,
            '$winner_game'      => $winner_game,
            'winner_class'      => $winner_class,
            'player1_cards'     => '',
            'computer_cards'    => '',
            'wins'              => $wins,
            'ties'              => $ties,
            'losses'            => $losses,
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
        $winner_game         = "";
        $winner_class        = "";
        $player1_card_path    = "";
        $computer_card_path   = "";

        $choice = $this->app->input('choice', '');

        if ($choice == 'reset') {
            session_destroy();
        } else {
            $round          = $this->app->sessionGet('round');
            $game_id        = $this->app->sessionGet('game_id');
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
                $winner_class =  "success";
            } else if ($winner == "Computer") {
                $winner_class =  "danger";
            } else if ($winner == "Tie") {
                $winner_class =  "warning";
            }

            # save move to the DB
            $data = [
                'game_id'               => $game_id,
                'number'                => $round,
                'player_card'           => $player1_card->getName(),
                'player_card_class'     => $player1_card->getClassName(),
                'computer_card'         => $computer_card->getName(),
                'computer_card_class'   => $computer_card->getClassName(),
                'winner'                => $winner,
                'winner_class'          => $winner_class,
                'choice'                => $choice,
                'player_card_count'     => sizeof($player1_cards),
                'computer_card_count'   => sizeof($computer_cards)
            ];

            $this->app->db()->insert('moves', $data);

            $round++;

            # save the cards back to the session for the game to continue
            $this->app->sessionSet('player1_cards', $player1_cards);
            $this->app->sessionSet('computer_cards', $computer_cards);

            # There is an edge case where the last round is a tie (and one user is out of cards after)
            # e.g. tie isn't an acceptiable outcome for the game but is for a round.
            # For that reason, we can't simply use the last $winner value in the view 

            if (count($computer_cards) == 0 || count($player1_cards) == 0) {
                if (count($computer_cards) == 0 && count($player1_cards) == 0) {
                    $winner_game = "Tie";
                    $status = 'tie';
                } else if (count($computer_cards) == 0) {
                    $winner_game = "You";
                    $status = 'won';
                } else if (count($player1_cards) == 0) {
                    $winner_game = "Computer";
                    $status = 'lost';
                }

                $score = count($player1_cards) - count($computer_cards);

                # update DB with game info
                # https://www.tutorialspoint.com/mysql/mysql-update-query.htm

                $sql = 'UPDATE games SET status = :status WHERE id = :id';

                $data = [
                    'id'        => $game_id,
                    'status'    => $status,
                    'score'     => $score,
                ];

                $this->app->db()->run($sql, $data);
            }
        }

        # calc stats
        $winpercent     = round(($wins / $round) * 100, 2);
        $losspercent    = round(($losses / $round) * 100, 2);
        $tiepercent     = round(($ties / $round) * 100, 2);

        $data = [
            'round'             => $round,
            'winner'            => $winner,
            'winner_game'       => $winner_game,
            'winner_class'      => $winner_class,
            'player1_cards'     => $player1_cards,
            'computer_cards'    => $computer_cards,
            'wins'              => $wins,
            'ties'              => $ties,
            'losses'            => $losses,
            'winpercent'        => $winpercent,
            'losspercent'       => $losspercent,
            'tiepercent'        => $tiepercent,
            'player1CardPath'   => $player1_card_path,
            'computerCardPath'  => $computer_card_path,
            'results'           => $results,

        ];

        $this->app->redirect('/play', $data);
    }

    public function player()
    {
        $user_id = $this->app->param('id');

        if (is_null($user_id)) {
            $this->app->redirect('/'); # send them back to the home page; could add in error message in the future
        }

        $user = $this->app->db()->findByColumn('users', 'id', '=', $user_id);

        if (empty($user)) {
            $this->app->redirect('/'); # send them back to the home page; could add in error message in the future
        }

        $name = $user[0]['name'];

        $games = $this->app->db()->findByColumn('games', 'user_id', '=', $user_id);

        if (empty($games)) {
            $this->app->redirect('/'); # send them back to the home page; could add in error message in the future
        }

        $data = [
            'name'    =>  $name,
            'games'   =>  $games,
        ];

        return $this->app->view('/player', $data);
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