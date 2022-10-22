<!DOCTYPE html>
<html lang='en'>

<head>

    <title>Project 2 - Interactive War Card Game </title>
    <meta charset='utf-8'>
    <link href=data:, rel=icon>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>
        Interactive War Card Game
    </h1>

    <h2>
        Instructions:
    </h2>
    <ul>
        <li>Each player starts with half the deck (26 cards), shuffled in a random order.</li>
        <li>For each round, a card is picked from the “top” of each player’s cards.</li>
        <li>The Human player has the <b>choice of playing that top card or re-shuffling</b> the deck and playing that
            card instead</li>
        <li>Whoevers card is highest wins that round and keeps both cards.</li>
        <li>If the two cards are of the same value, it’s a tie and those cards are discarded.</li>
        <li>The player who ends up with 0 cards is the loser.</li>
        <li>Note: Aces are always higher than Kings in this game</li>
    </ul>

    <table>
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
                    <label for='keep' class="GreenButton">Keep</label>
                </td>
                <td>
                    <input type='radio' id='shuffle' name='choice' value='random'>
                    <label for='shuffle' class="GreenButton">Get random card</label>
                </td>
            </tr>
            <tr>
                <td>
                    <button type='submit' class="GreenButton">Play</button>
                </td>
        </form>
        <form method='POST' action='process.php'>
            <td>
                <input type='hidden' id='reset' name='choice' value='reset'>
                <button type='submit' class="RedButton">Reset</button>
            </td>
        </form>
        </tr>
    </table>

    <?php if (isset($results)) { ?>
    <h2>Stats</h2>
    <div>
        Total Rounds: <?php echo $round - 1; ?>
        Wins: <?php echo $wins; ?>
        Losses: <?php echo $losses; ?>
        Ties: <?php echo $ties; ?>
    </div>

    <h2>History</h2>
    <table>
        <tr>
            <th>Round #</th>
            <th>Player 1 card</th>
            <th>Computer card</th>
            <th>Winner</th>
            <th>Choice</th>
            <th>Player 1 cards left</th>
            <th>Player 2 cards left</th>
        </tr>
        <?php foreach ($results as $result) { ?>
        <tr>
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
                <span class=<?php echo $result['Winner class']; ?>>
                    <?php echo $result['Winner']; ?>
                </span>
            </td>
            <td> <?php echo $result['Choice']; ?> </td>
            <td> <?php echo $result['Player 1 cards left']; ?> </td>
            <td> <?php echo $result['Computer cards left']; ?> </td>
        </tr>
        <?php } ?>
    </table>
    <?php } ?>
</body>

</html>