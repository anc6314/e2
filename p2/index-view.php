<!DOCTYPE html>
<html lang='en'>

<head>

    <title>Project 2 - Blackjack</title>
    <meta charset='utf-8'>
    <link href=data:, rel=icon>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>
        Blackjack
    </h1>

    <h2>
        Mechanics
    </h2>
    <ul>
        <li></li>
        <li></li>
        <li></li>
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
                        <span class= <?php echo $class; ?> >
                            <?php echo $value; ?>
                        </span>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>

</body>

</html>