<!DOCTYPE html>
<html lang='en'>

<head>

    <title>Project 1 - War (card game) Simulator</title>
    <meta charset='utf-8'>
    <link href=data:, rel=icon>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>
        War (card game) Simulator
    </h1>

    <h2>
        Mechanics
    </h2>
    <ul>
        <li>Each player starts with half the deck (26 cards), shuffled in a random order.</li>
        <li>For each round, a card is picked from the “top” of each player’s cards.</li>
        <li>Whoevers card is highest wins that round and keeps both cards.</li>
        <li>If the two cards are of the same value, it’s a tie and those cards are discarded.</li>
        <li>The player who ends up with 0 cards is the loser.</li>
        <li>Note: Aces are always higher than Kings in this game</li>
    </ul>

    <h2>
        Results
    </h2>
    <ul>
        <li>Rounds played: <?php echo $round; ?></li>
        <li>Winner: <?php echo $winner; ?></li>
    </ul>

    <h2>Rounds</h2>
    <table>
        <tr>
            <th>Round #</th>
            <th>Player 1 card</th>
            <th>Player 2 card</th>
            <th>Winner</th>
            <th>Player 1 cards left</th>
            <th>Player 2 cards left</th>
        </tr>
        <?php foreach ($results as $round) { ?>
            <tr>
                <?php foreach ($round as $key => $value) { ?>
                    <td>
                    <?php if ($key == "Player 1 card" || $key == "Player 2 card") {
                        echo "<span class=\"card\">" . $value . "</span>";
                    } else {
                        echo $value;
                    }
                } ?>
                    </td>
            </tr>
        <?php } ?>
    </table>

</body>

</html>