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

    public function getImagePath() # image for the bigger GUI / Playing the game
    {
        # example: /images/clubs_2.png
        return "/images/" . strtolower($this->suit->name) . "_" . $this->getImageValue() . ".png";
    }

    public function getImageValue() # translate number to name for building image path
    {
        if ($this->value <= 10) {
            return $this->value;
        } # 2-10 don't change

        switch ($this->value) {
            case 11:
                return "jack";
            case 12:
                return "queen";
            case 13:
                return "king";
            case 14:
                return "ace";
        }
    }

    public function getDisplayValue() # translate number to face cards for history feed
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

    public function getClassName() #for CSS / output in history feed
    {
        switch ($this->suit) {

            case Suit::Hearts:
                return "Redcard";

            case Suit::Diamonds:
                return "Redcard";

            case Suit::Clubs:
                return "Blackcard";

            case Suit::Spades:
                return "Blackcard";
        }
    }
}