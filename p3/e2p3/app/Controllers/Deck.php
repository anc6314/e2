<?php

namespace App\Controllers;

require("Card.php"); # Note: requires php 8.1 or higher for enum support

class Deck
{
    # Properties
    public $cards = array();

    # Methods
    public function __construct(bool $shuffle = false)
    {

        # Create a deck of 52 cards
        foreach (Suit::cases() as $suit) {
            for ($i = 2; $i < 15; ++$i) {
                $card = new Card($suit, $i);
                array_push($this->cards, $card);
            }
        }

        if ($shuffle) {
            shuffle($this->cards);
        }
    }
}