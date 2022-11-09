<?php
require __DIR__ . '/vendor/autoload.php';

use RPS\Game;

/*
$game = new Game(true);

# Each invocation of the `play` method will play and track a new round of player (given move) vs. computer
$game->play('rock');

var_dump($game->getResults());
*/

class RPS extends Game
{
    protected $moves = ['heads', 'tails'];

    protected function determineOutcome($playerMove, $computerMove)
    {
        if ($playerMove == 'heads' and $computerMove == 'tails') {
            return 'won';
        } else {
            return 'lost';
        }
    }
}

$rps = new RPS();
var_dump($rps->play('heads'));