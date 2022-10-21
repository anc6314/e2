<?php
require("Suit.php"); # Note: requires php 8.1 or higher for enum support

class Card
{
    # Properties
    public $suit = "";
    public $value = 2;      # Used to find the highest card / compare

    # Methods
    public function __construct(Suit $suit, int $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getName() # human redable card name
    {
        return $this->getDisplayValue() . " " . $this->suit->value;
    }

    public function getDisplayValue() # translate number to face cards
    {
        if ($this->value <= 10) {
            return $this->value;
        } # 2-10 don't change

        switch ($this->value) {
            case 11:
                return "J"; # Jack
            case 12:
                return "Q"; # Queen
            case 13:
                return "K"; # King
            case 14:
                # Note:  Aces are always higher than Kings in this game
                # https://www.hellaentertainment.com/blog/card-games/war/
                return "A"; # Ace
        }
    }

    public function getClassName() #for CSS / output
    {
        switch ($this->suit) {

            case Suit::Hearts:
                return "Red";

            case Suit::Diamonds:
                return "Red";

            case Suit::Clubs:
                return "Black";

            case Suit::Spades:
                return "Black";
        }
    }
}