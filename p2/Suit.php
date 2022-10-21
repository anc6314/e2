<?php
# NOTE: Enum's didn't appear until php 8.1; so that is required for this code
enum Suit: string
{
    case Hearts   = "♥";
    case Diamonds = "♦";
    case Clubs    = "♧";
    case Spades   = "♤";
}