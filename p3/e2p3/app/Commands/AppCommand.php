<?php

namespace App\Commands;

use Faker\Factory;
use App\Controllers\Deck; # Note: requires php 8.1 or higher for enum support

class AppCommand extends Command
{
    public function migrate()
    {
        # Note that the *createTable* method automatically adds an auto-incrementing 
        # primary key column named `id` so you don’t have to include that in your array of columns.
        $this->app->db()->createTable('users', [
            'name'  => 'varchar(255)',
        ]);

        # note that status is either playing, won, or lost
        $this->app->db()->createTable('games', [
            'user_id'   => 'int',
            'status'    => 'varchar(255)',
            'score'     => 'int'
        ]);

        $this->app->db()->createTable('moves', [
            'game_id'               => 'int',
            'number'                => 'int',
            'player_card'           => 'varchar(255)',
            'player_card_class'     => 'varchar(255)',
            'computer_card'         => 'varchar(255)',
            'computer_card_class'   => 'varchar(255)',
            'winner'                => 'varchar(255)',
            'winner_class'          => 'varchar(255)',
            'choice'                => 'varchar(255)',
            'player_card_count'     => 'int',
            'computer_card_count'   => 'int'
        ]);

        dump('Migration complete; check the database for your new tables.');
    }


    public function seedUsers()
    {
        # Instantiate a new instance of the Faker\Factory class
        $faker = Factory::create();

        # Use a loop to create 5 users
        for ($i = 0; $i < 5; $i++) {
            $user = ['name' => $faker->name,];
            $this->app->db()->insert('users', $user);
        }

        dump('Sample users have been created!');
    }

    public function seedGames()
    {
        $users = $this->app->db()->all('users');

        foreach ($users as $user) {
            # create a random number of games 1 to 3; 
            # limited number of games because a game could have 200+ rounds and don't want the seedMoves function to run too long

            $games = rand(1, 3);

            for ($i = 0; $i <= $games; $i++) {
                $score = rand(-20, 20); # this won't match the actual total from the rounds but just for simplicy sake
                $status = ($score > 0) ? 'won' : 'lost'; # again this won't match the moves below but just for simplicy sake

                $data = [
                    'user_id'   => $user['id'],
                    'status'    => $status,
                    'score'     => $score,
                ];

                $this->app->db()->insert('games', $data);
            }
        }

        dump('Sample rounds have been created for each existing user!');
    }

    public function seedMoves()
    {
        # NOTE that this script may take some time to run as it will simulate the playing of games!
        dump('Starting to simulate games.  Warning: this may take some time!');
        $games = $this->app->db()->all('games');

        foreach ($games as $game) {
            $round = 1;
            $winner = "";

            # now let's setup the game of war!
            $deck = new Deck(true); # get a shuffled deck of cards

            # Each player starts with half the deck (26 cards)
            while ($deck->cards) {
                $player_cards[]  = array_shift($deck->cards); # Human player
                $computer_cards[] = array_shift($deck->cards);
            }

            # Let's play war!
            while (sizeof($player_cards) > 0 && sizeof($computer_cards) > 0) {
                $player_card = $player_cards[0];
                $computer_card = $computer_cards[0];

                $choice = (rand(0, 1) > 0) ? 'keep' : 'random'; # player will make choices at random; not realistic but this is sample data

                # Card with highest value wins that round and keeps both cards.
                if ($player_card->value > $computer_card->value) {
                    $winner = "You";

                    # take card from other player and put at end of deck
                    $card = array_shift($computer_cards);
                    array_push($player_cards, $card);

                    # move our winning card to end of the deck
                    array_push($player_cards, $player_cards[0]);
                    array_shift($player_cards);
                } elseif ($computer_card->value > $player_card->value) {
                    $winner = "Computer";
                    # take card from other player and put at end of deck
                    $card = array_shift($player_cards);
                    array_push($computer_cards, $card);

                    # move our winning card to end of the deck
                    array_push($computer_cards, $computer_cards[0]);
                    array_shift($computer_cards);
                } else {
                    $winner = "Tie";
                    #  it’s a tie and those cards are discarded.
                    array_shift($player_cards);
                    array_shift($computer_cards);
                }

                if ($winner == "You") {
                    $winner_class =  "success";
                } else if ($winner == "Computer") {
                    $winner_class =  "danger";
                } else if ($winner == "Tie") {
                    $winner_class =  "warning";
                }

                $data = [
                    'game_id'               => $game['id'],
                    'number'                => $round,
                    'player_card'           => $player_card->getName(),
                    'player_card_class'     => $player_card->getClassName(),
                    'computer_card'         => $computer_card->getName(),
                    'computer_card_class'   => $computer_card->getClassName(),
                    'winner'                => $winner,
                    'winner_class'          => $winner_class,
                    'choice'                => $choice,
                    'player_card_count'     => sizeof($player_cards),
                    'computer_card_count'   => sizeof($computer_cards)
                ];

                $this->app->db()->insert('moves', $data);

                $round++;
            }
        }

        dump('Sample moves have been created for each existing game!');
    }

    public function fresh()
    {
        $this->migrate();
        $this->seedUsers();
        $this->seedGames();
        $this->seedMoves();
    }
}