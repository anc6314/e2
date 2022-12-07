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
        $name  = $this->app->sessionGet('name');

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
            'name'    =>  $name,
        ];

        return $this->app->view('/game', $data);
    }

    public function play()
    {
        /**
         *  Step 1: Read in vars: cards/user/game from session
         *  Step 2: Check for first round and if so start new round logic
         *  Step 3: If not first round, load up stats history from DB
         *  Step 4: flash data and Render the play view
         */

        # Step 1: Read in vars: cards/user/game from session
        $round          = $this->app->sessionGet('round');
        $user_id        = $this->app->sessionGet('user_id');
        $game_id        = $this->app->sessionGet('game_id');

        # default vars to prevent undefined errors in view
        $winner         = "";
        $winner_class   = "";
        $winner_game    = "";

        $wins           = 0;
        $ties           = 0;
        $losses         = 0;

        $win_percent     = 0;
        $loss_percent    = 0;
        $tie_percent     = 0;

        $results        = array(); # this will be an array of arrays that will used to output in view

        # Is this the first round?
        if (is_null($round)) {
            # Set first round to prevent re-init of game
            $round = 1;
            $this->app->sessionSet('round', $round);

            # now let's setup a new game of war!
            $deck = new Deck(true); # get a shuffled deck of cards

            # Each player starts with half the deck (26 cards)
            while ($deck->cards) {
                $player1_cards[]  = array_shift($deck->cards); # Human player
                $computer_cards[] = array_shift($deck->cards);
            }

            # save our card data for the game
            $this->app->sessionSet('player1_cards',  $player1_cards);
            $this->app->sessionSet('computer_cards', $computer_cards);

            # save game to DB and set game ID in session
            $data = [
                'user_id'   => $user_id,
                'status'    => 'playing',
                'score'     => 0,
            ];

            $game_id = $this->app->db()->insert('games', $data);
            $this->app->sessionSet('game_id',  $game_id);
        } else if ($round == 1) {
            # User has already started a game, never made any moves, and reloaded the page or wants to resume the existing game
            # load cards from the session
            $player1_cards  = $this->app->sessionGet('player1_cards');
            $computer_cards = $this->app->sessionGet('computer_cards');
        } else if ($round > 1) {
            # Step 3: If not first round, load up stats history from DB

            $sql = 'SELECT winner, count(*) FROM moves WHERE game_id = :game_id GROUP BY winner';
            $data = ['game_id' => $game_id];

            $executed = $this->app->db()->run($sql, $data);
            $moves = $executed->fetchAll();

            # https://1bestcsharp.blogspot.com/2016/07/php-mysql-pdo-using-foreach-loop.html
            foreach ($moves as $move) {
                $value = $move['count(*)'];
                if ($move['winner'] == "Computer") {
                    $losses = $value;
                } else if ($move['winner'] == "You") {
                    $wins = $value;
                } else if ($move['winner'] == "Tie") {
                    $ties = $value;
                }
            }

            $results = $this->app->db()->findByColumn('moves', 'game_id', '=', $game_id);

            # calc stats - note that we have to subtract the current round for accuate states
            $win_percent     = ($wins == 0)  ? 0 : round(($wins / ($round - 1)) * 100, 2);
            $loss_percent    = ($losses == 0) ? 0 : round(($losses / ($round - 1)) * 100, 2);
            $tie_percent     = ($ties == 0)  ? 0 : round(($ties / $round) * 100, 2);

            # load cards from the session
            $player1_cards  = $this->app->sessionGet('player1_cards');
            $computer_cards = $this->app->sessionGet('computer_cards');

            # pull the winner and winner class from the last row
            # https://www.geeksforgeeks.org/php-end-function/

            $winner       = end($results)['winner'];
            $winner_class = end($results)['winner_class'];
        }

        # Step 4: flash data and Render the play view
        $player1_card_path    = $player1_cards[0]->getImagePath();
        $computer_card_path   = $computer_cards[0]->getImagePath();

        $data = [
            'game_id'               => $game_id,
            'round'                 => $round,
            'winner'                => $winner,
            '$winner_game'          => $winner_game,
            'winner_class'          => $winner_class,
            'wins'                  => $wins,
            'ties'                  => $ties,
            'losses'                => $losses,
            'win_percent'           => $win_percent,
            'loss_percent'          => $loss_percent,
            'tie_percent'           => $tie_percent,
            'player1_card_path'     => $player1_card_path,
            'computer_card_path'    => $computer_card_path,
            'results'               => $results,

        ];

        return $this->app->view('/play', $data);
    }

    public function process()
    {
        /**
         *  Step 1: Read in vars: cards/user from session
         *  Step 2: Check for need to shuffle player 1 cards
         *  Step 3: Process game / determine winner
         *  Step 4: Save results to DB for this round
         *  Step 5: Save card state to session - not in DB
         *  Step 6: Determine if this game is over and if so:
         *          save to DB, and start new game
         *  Step 7: Calc stats
         *  Step 8: Send user back to the play page with updated data
         */

        # Step 1: read in vars: cards/user from session

        # vars from the forms go here
        $choice = $this->app->input('choice', ''); #keep card or draw random?

        # these vars are stored in the session and not DB
        $game_id        = $this->app->sessionGet('game_id');
        $round          = $this->app->sessionGet('round');
        $player1_cards  = $this->app->sessionGet('player1_cards');
        $computer_cards = $this->app->sessionGet('computer_cards');

        # Step 2: Check for need to shuffle player 1 cards
        if ($choice == 'random') {
            shuffle($player1_cards);
        }

        # Step 3: process game / determine winner
        $player1_card = $player1_cards[0];
        $computer_card = $computer_cards[0];

        # Card with highest value wins that round and keeps both cards.
        if ($player1_card->value > $computer_card->value) {
            $winner = "You";
            $winner_class =  "success";

            # take card from other player and put at end of deck
            $card = array_shift($computer_cards);
            array_push($player1_cards, $card);

            # move our winning card to end of the deck
            array_push($player1_cards, $player1_cards[0]);
            array_shift($player1_cards);
        } elseif ($computer_card->value > $player1_card->value) {
            $winner = "Computer";
            $winner_class =  "danger";
            # take card from other player and put at end of deck
            $card = array_shift($player1_cards);
            array_push($computer_cards, $card);

            # move our winning card to end of the deck
            array_push($computer_cards, $computer_cards[0]);
            array_shift($computer_cards);
        } else {
            $winner = "Tie";
            $winner_class =  "warning";
            #  it’s a tie and those cards are discarded.
            array_shift($player1_cards);
            array_shift($computer_cards);
        }

        # Step 4: save results to DB for this round
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
        $this->app->sessionSet('round', $round);

        # Step 5: save card state to session - not in DB
        $this->app->sessionSet('player1_cards', $player1_cards);
        $this->app->sessionSet('computer_cards', $computer_cards);


        #Step 6: determine if this game is over and if so: save to DB, and start new game

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

            # start new game
            // TODO: start New game here            
        }


        # *  Step 7: Calc stats

        $sql = 'SELECT winner, count(*) FROM moves WHERE game_id = :game_id GROUP BY winner';
        $data = ['game_id' => $game_id];

        $executed = $this->app->db()->run($sql, $data);
        $moves = $executed->fetchAll();

        # https://1bestcsharp.blogspot.com/2016/07/php-mysql-pdo-using-foreach-loop.html
        foreach ($moves as $move) {
            $value = $move['count(*)'];
            if ($move['winner'] == "Computer") {
                $losses = $value;
            } else if ($move['winner'] == "You") {
                $wins = $value;
            } else if ($move['winner'] == "Tie") {
                $ties = $value;
            }
        }

        $win_percent     = round(($wins / $round) * 100, 2);
        $loss_percent    = round(($losses / $round) * 100, 2);
        $tie_percent     = round(($ties / $round) * 100, 2);

        $results = $this->app->db()->findByColumn('moves', 'game_id', '=', $game_id);

        # Step 8: send user back to the play page with updated data
        $player1_card_path    = $player1_cards[0]->getImagePath();
        $computer_card_path   = $computer_cards[0]->getImagePath();

        $data = [
            'game_id'               => $game_id,
            'winner'                => $winner,
            'winner_game'           => $winner_game,
            'winner_class'          => $winner_class,
            'wins'                  => $wins,
            'ties'                  => $ties,
            'losses'                => $losses,
            'win_percent'           => $win_percent,
            'loss_percent'          => $loss_percent,
            'tie_percent'           => $tie_percent,
            'player1_card_path'     => $player1_card_path,
            'computer_card_path'    => $computer_card_path,
            'results'               => $results,

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

        $this->app->validate([
            'name' => 'required',
        ]);

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

    public function round()
    {
        # NOTE: The round number and the move_id are NOT the same; move_id is the primary key but round number can repeate
        $move_id = $this->app->param('id');

        if (is_null($move_id)) {
            $this->app->redirect('/'); # send them back to the home page; could add in error message in the future
        }

        $result = $this->app->db()->findByColumn('moves', 'id', '=', $move_id);

        # https://www.geeksforgeeks.org/how-to-check-whether-an-array-is-empty-using-php/
        if (count($result) == 0) {
            $this->app->redirect('/'); # send them back to the home page; could add in error message in the future
        }

        # make card objects to match this round so we can display them on the page
        # Note: this code assumes the DB has valid data; in production we would want better error handling
        # We would also likely move some of this logic to the card class to meet separation of concerns / dry code
        #https://www.w3schools.com/php/func_string_explode.asp

        $value              = Card::getValueFromDisplay(explode(" ", $result[0]['player_card'])[0]);
        $suit               = explode(" ", $result[0]['player_card'])[1];
        $player1_card       = new Card(Suit::from($suit), $value);
        $player1_card_path  = $player1_card->getImagePath();

        $value               = Card::getValueFromDisplay(explode(" ", $result[0]['computer_card'])[0]);
        $suit                = explode(" ", $result[0]['computer_card'])[1];
        $computer_card       = new Card(Suit::from($suit), $value);
        $computer_card_path  = $computer_card->getImagePath();

        $data = [
            'result'              =>  $result[0],
            'player1_card_path'   =>  $player1_card_path,
            'computer_card_path'  =>  $computer_card_path,
        ];

        return $this->app->view('/round', $data);
    }
}