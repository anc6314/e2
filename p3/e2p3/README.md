# Project 3
+ By: Anthony Coleman
+ URL: <http://e2p3.anthonycoleman.me/>

## Graduate requirement

+ [X] I have integrated testing into my application
+ [ ] I am taking this course for undergraduate credit and have opted out of integrating testing into my application

## Outside resources
+ <https://1bestcsharp.blogspot.com/2016/07/php-mysql-pdo-using-foreach-loop.html>
+ <https://www.geeksforgeeks.org/php-end-function/>
+ <https://www.tutorialspoint.com/mysql/mysql-update-query.htm>
+ <https://1bestcsharp.blogspot.com/2016/07/php-mysql-pdo-using-foreach-loop.html>
+ <https://stackoverflow.com/questions/37458399/count-all-rows-by-status>
+ <https://www.geeksforgeeks.org/how-to-check-whether-an-array-is-empty-using-php/>
+ <https://www.w3schools.com/php/func_string_explode.asp>
+ <https://stackoverflow.com/questions/40406418/cannot-declare-class-controller-because-the-name-is-already-in-use>
+ <https://www.w3schools.com/php/func_string_substr.asp>
+ <https://www.tutorialkart.com/php/php-convert-string-to-int/>
+ <https://tekeye.uk/playing_cards/svg-playing-cards>

## Notes for instructor
The game has already been seeded with players.  You can use their name to 'login' (w/o password) as them or you can create your own player.

NOTE: Due to the use of enums this project requires php 8.1 in order to run
+ <https://www.digitalocean.com/community/tutorials/how-to-install-php-8-1-and-set-up-a-local-development-environment-on-ubuntu-22-04>
+ <https://www.cloudbooklet.com/how-to-install-or-upgrade-php-8-1-on-ubuntu-20-04/>

## Codeception testing output
```
Codeception PHP Testing Framework v5.0.5 https://helpukrainewin.org

Tests.Acceptance Tests (6) ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
gamePageCest: Test game detail
Signature: Tests\Acceptance\gamePageCest:testGameDetail
Test: tests/Acceptance/GamePageCest.php:testGameDetail
Scenario --
 I am on page "/game?id=1"
 I see in title "Move history for Game ID"
 I see "History"
 I grab multiple "[test=move-link]"
 Found moves: 200
 I assert greater than or equal 26,200
 PASSED 

IndexPageCest: Test blank name
Signature: Tests\Acceptance\IndexPageCest:testBlankName
Test: tests/Acceptance/IndexPageCest.php:testBlankName
Scenario --
 I am on page "/"
 I see in title "War Card Game - Home"
 I see "Would you like to play a game?"
 I click "[test=register-button]"
 I see "The value for name can not be blank"
 PASSED 

IndexPageCest: Test registration
Signature: Tests\Acceptance\IndexPageCest:testRegistration
Test: tests/Acceptance/IndexPageCest.php:testRegistration
Scenario --
 I am on page "/"
 I see in title "War Card Game - Home"
 I see "Would you like to play a game?"
 I fill field "[test=register-name]","Bob Smith"
 I click "[test=register-button]"
 I see in current url "/play"
 PASSED 

movePageCest: Test move detail
Signature: Tests\Acceptance\movePageCest:testMoveDetail
Test: tests/Acceptance/MovePageCest.php:testMoveDetail
Scenario --
 I am on page "/round?id=1"
 I see in title "Details for Game"
 I see element "[test=player-card]"
 I see element "[test=computer-card]"
 I see "Results"
 I grab multiple "[test=move-number]"
 Found moves: 1
 I assert equals 1,1
 PASSED 

PlayPageCest: Test play
Signature: Tests\Acceptance\PlayPageCest:testPlay
Test: tests/Acceptance/PlayPageCest.php:testPlay
Scenario --
 I am on page "/"
 I fill field "[test=register-name]","Bob Smith"
 I click "[test=register-button]"
 I see in title "Playing war!"
 I see "Current Round: 1"
 I see "cards remaining."
 I grab attribute from "[test=player-card-image]","src"
 I grab attribute from "[test=computer-card-image]","src"
 Player card: 2
 Computer card: 14
 I fill field "[test=shuffle-radio]","keep"
 I click "[test=play-button]"
 I see element "[test=lost-output]"
 I see "History"
 PASSED 

PlayerPageCest: Test player detail
Signature: Tests\Acceptance\PlayerPageCest:testPlayerDetail
Test: tests/Acceptance/PlayerPageCest.php:testPlayerDetail
Scenario --
 I am on page "/player?id=1"
 I see in title "Player History for "
 I see "Games Played"
 I grab multiple "[test=game-link]"
 Found games: 4
 I assert greater than or equal 1,4
 PASSED 

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Time: 00:00.481, Memory: 12.00 MB

OK (6 tests, 24 assertions)
```
