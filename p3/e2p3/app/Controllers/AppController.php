<?php

namespace App\Controllers;

class AppController extends Controller
{
    /**
     * This method is triggered by the route "/"
     */
    public function index()
    {
        return $this->app->view('index');
    }

    public function play()
    {

        # setup defaults

        $results = array(); # this will be an array of arrays that will used to output in view
        $wins       = 0;
        $ties       = 0;
        $losses     = 0;
        $round      = 1;
        $winner     = "";
        $winnerGame = "";
        $winnerClass = "";
        $player1CardPath = "/images/spades_jack.png";
        $computerCardPath = "/images/clubs_6.png";


        $choice = $this->app->input('choice', '');

        if ($choice == 'reset') {
            session_destroy();
        } else {
            # Set vars from session
            $round = 3;
            $wins = 2;
            $ties = 0;
            $winner = 'Computer';

            # calc stats
            $losses         = $round - ($wins + $ties);
            $winpercent     = round(($wins / $round) * 100, 2);
            $losspercent    = round(($losses / $round) * 100, 2);
            $tiepercent     = round(($ties / $round) * 100, 2);
        }

        if ($winner == "You") {
            $winnerClass =  "success";
        } else if ($winner == "Computer") {
            $winnerClass =  "danger";
        } else if ($winner == "Tie") {
            $winnerClass =  "warning";
        }

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
            'computerCardPath'   => $computerCardPath,

        ];

        return $this->app->view('play', $data);
    }
}