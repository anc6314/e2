# Project 1
+ By: Anthony Coleman
+ URL: <http://e2p1.anthonycoleman.me/>

## Game Planning
+ Create an array of strings that contain a string made up of numeric value and a suite code - aka a deck of 52 cards.
+ Shuffle the array of cards randomly
+ Divide the array of cards between 2 players
+ Create a main loop to run until one player is out of cards
+ During each run of the loop, pull the top card from each player and compare to determine the winner
+ Append the result of the round to an array for output in the view
+ Upon a tie, both cards are discarded but upon a win, the winner gets the cards to the bottom of their deck/array
+ In the view, display the results from the results array


## Outside Resources
        http://www.hackingwithphp.com/5/6/10/randomising-your-array
        https://stackoverflow.com/questions/7113231/how-to-iterate-over-an-array-of-arrays
        https://www.alt-codes.net/suit-cards.php
        https://www.php.net/manual/en/control-structures.switch.php
        https://www.w3schools.com/php/func_array_push.asp
        https://www.php.net/manual/en/function.array-keys.php
        https://www.w3schools.com/php/func_array.asp

## Notes for instructor
+ My version is a little different from your example in that Aces are the highest card.