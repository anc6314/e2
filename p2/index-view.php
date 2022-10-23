<!DOCTYPE html>
<html lang='en'>

<head>

    <title>Project 2 - Interactive War Card Game </title>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href=data:, rel=icon>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body class="body">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <div class="container-fluid background">
        <div class="row">
            <div class="col">
                <h1>
                    Interactive War Card Game
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h2>Stats</h2>
                <div>
                    Total Rounds: <?php echo $round - 1; ?>
                    <br />

                    <?php if ($round > 1) { ?>
                    Wins: <?php echo $wins; ?>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: <?php echo $wins / ($round - 1) * 100; ?>%;"
                            aria-valuenow="<?php echo $wins / ($round - 1) * 100; ?>" aria-valuemin="0"
                            aria-valuemax="100">
                            <?php echo round($wins / ($round - 1) * 100, 1); ?>%</div>
                    </div>

                    Losses: <?php echo $losses; ?>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar"
                            style="width: <?php echo $losses / ($round - 1) * 100; ?>%;"
                            aria-valuenow="<?php echo $losses / ($round - 1) * 100; ?>" aria-valuemin="0"
                            aria-valuemax="100">
                            <?php echo round($losses / ($round - 1) * 100, 1); ?>%</div>
                    </div>

                    Ties: <?php echo $ties; ?>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar"
                            style="width: <?php echo $ties / ($round - 1) * 100; ?>%;"
                            aria-valuenow="<?php echo $ties / ($round - 1) * 100; ?>" aria-valuemin="0"
                            aria-valuemax="100">
                            <?php echo round($ties / ($round - 1) * 100, 1); ?>%</div>
                    </div>
                    <br />
                    <div class="alert alert-<?php echo $_SESSION['winnerClass']; ?> ">
                        <?php if ($winner != "Tie") { ?>
                        <?php echo $winner; ?> won the last round!
                        <?php } else { ?>
                        The last round was a tie!
                        <?php } ?>
                    </div>

                    <?php } ?>

                </div>

            </div>
            <div class="col">
                <table>
                    <?php if ($winnerGame == "") { ?>
                    <tr>
                        <th>Your Card</th>
                        <th>Computer's Card</th>
                    </tr>
                    <tr>
                        <td>
                            <span>
                                <img src=<?php echo $_SESSION['player1_cards'][0]->getImagePath(); ?> />
                            </span>
                        </td>
                        <td>
                            <span>
                                <img src=<?php echo $_SESSION['computer_cards'][0]->getImagePath(); ?> />
                            </span>
                        </td>
                    </tr>
                    <form method='POST' action='process.php'>
                        <tr>
                            <td>
                                <input type='radio' id='keep' name='choice' value='keep' checked>
                                <label for='keep' class="black">Keep</label>
                            </td>
                            <td>
                                <input type='radio' id='shuffle' name='choice' value='random'>
                                <label for='shuffle' class="black">Get random card</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button class="btn btn-success" type='submit'>Play</button>

                            </td>
                    </form>
                    <?php } else { ?>

                    <div class="alert alert-primary" role="alert">
                        Game over, <?php echo strtolower($winnerGame) ?> won the game!
                    </div>
                    <?php } ?>

                    <form method='POST' action='process.php'>
                        <td>
                            <input type='hidden' id='reset' name='choice' value='reset'>
                            <button class="btn btn-danger" type='submit'>
                                <?php echo (isset($winnerGame)) ? "New Game" : "Reset" ?>
                            </button>
                        </td>
                    </form>

                    </tr>
                </table>
            </div>
            <div class="col black">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseInstructions"
                                    aria-expanded="false" aria-controls="collapseInstructions">
                                    <h2>
                                        Instructions:
                                    </h2>
                                </button>

                                <div id="collapseInstructions" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordion">
                                    <div class="card-body">

                                        <ul>
                                            <li>Each player starts with half the deck (26 cards), shuffled in a random
                                                order.
                                            </li>
                                            <li>For each round, a card is picked from the “top” of each player’s cards.
                                            </li>
                                            <li>The Human player has the <b>choice of playing that top card or
                                                    re-shuffling</b>
                                                the
                                                deck and playing
                                                that
                                                card instead</li>
                                            <li>Whoevers card is highest wins that round and keeps both cards.</li>
                                            <li>If the two cards are of the same value, it’s a tie and those cards are
                                                discarded.
                                            </li>
                                            <li>The player who ends up with 0 cards is the loser.</li>
                                        </ul>

                                    </div>
                                </div>
                        </div>

                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($results)) { ?>
        <div class="row">
            <div class="col">
            </div>
            <div class="col-8">
                <h2>History</h2>
                <table class="table table-hover">
                    <tr class="table-primary">
                        <th scope="col">Round #</th>
                        <th scope="col">Player's card</th>
                        <th scope="col">Computer's card</th>
                        <th scope="col">Winner</th>
                        <th scope="col">Choice</th>
                        <th scope="col">Player cards left</th>
                        <th scope="col">Computer cards left</th>
                    </tr>
                    <?php foreach ($results as $result) { ?>
                    <tr class="table-<?php echo $result['Winner class']; ?> black">
                        <td> <?php echo $result['Round']; ?> </td>
                        <td>
                            <span class=<?php echo $result['Player 1 card class']; ?>>
                                <?php echo $result['Player 1 card']; ?>
                            </span>
                        </td>
                        <td>
                            <span class=<?php echo $result['Computer card class']; ?>>
                                <?php echo $result['Computer card']; ?>
                            </span>
                        </td>
                        <td>
                            <?php echo $result['Winner']; ?>
                        </td>
                        <td> <?php echo $result['Choice']; ?> </td>
                        <td> <?php echo $result['Player 1 cards left']; ?> </td>
                        <td> <?php echo $result['Computer cards left']; ?> </td>
                    </tr>
                    <?php } ?>
                </table>
                <?php } ?>
            </div>
            <div class="col">
            </div>
        </div>
    </div>

</body>

</html>