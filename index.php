<?php
// REQUIRE CLASSES
require_once("class/Database.class.php");
require_once("class/Player.class.php");
require_once("class/Dice.class.php");
require_once("functions.php");
// DATABASE CONNEXION
$database = new Database("localhost", "combat", "root", "");
$database->connect();
$database->prepReq("SELECT * FROM personnage");
$dice = new Dice();
$listOfCharacters = $database->fetchData();
if (isset($_GET['new-player'])) {
    $newPlayer = $_GET['new-player'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de combat</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.cdnfonts.com/css/jetbrains-mono" rel="stylesheet">
</head>

<body>
    <audio src="assets/audio/SFtheme.mp3" autoplay></audio>

    <form method="get" action="index.php">
        <input type="text" name="new-player" autofocus>
        <input type="submit" value="Créer ton player">
        <?php
        if (isset($newPlayer)) {
            $database->create($newPlayer);
        }

        ?>
    </form>
    <p class="or">OR</p>

    <table>
        <tr>
            <th>Personnage</th>
            <th>PV</th>
        </tr>
        <?php
        foreach ($listOfCharacters as $character) {
            echo " <tr>
                        <td>$character[1] </td>
                        <td>$character[2] </td>
                        </tr>
                ";
        }
        ?>
    </table>

    <form method="get" action="index.php">

        <select name="player1" id="select-player1">
            <option value="">--Select Player 1--</option>
            <?php foreach ($listOfCharacters as $character) : ?>
                <option name="<?= $character[1] ?>"><?= $character[1] ?></option> };
            <?php endforeach ?>
        </select>

        <select name="player2" id="select-player2">
            <option value="">--Select Player 2--</option>
            <?php foreach ($listOfCharacters as $character) : ?>
                <option name="<?= $character[1] ?>"><?= $character[1] ?></option> };
            <?php endforeach ?>
        </select>

        <input type='submit' value='Select CHARACTERS and fight!'>
    </form>
    <div class="fighting">

        <div class="fighter-left">
            <h2>PLAYER 1</h2>
            <?php
            if (isset($_GET['player1'])) :
                echo "<h3 class='style-player1'>" . $_GET['player1'] . "</h3>";
                $infos = $database->read($_GET['player1']);
                $infos = $database->fetchData();
                foreach ($infos as $info) {
                    $nom = $info[1];
                    $PV = $info[2];
                    $power = $dice->launchDice();
                    $player1 = new Player($nom, $PV, $power, 0);
                }
            endif;
            ?>
        </div>
        <img class="vs" src="assets/img/versus.png">
        <div class="fighter-right">


            <h2>PLAYER 2</h2>
            <?php if (isset($_GET['player2'])) :
                echo "<h3 class='style-player2'>" . $_GET['player2'] . "</h3>";
                $infos = $database->read($_GET['player2']);
                $infos = $database->fetchData();
                foreach ($infos as $info) {
                    $nom = $info[1];
                    $PV = $info[2];
                    $power = $dice->launchDice();
                    $player2 = new Player($nom, $PV, $power, 0);
                }
            endif;
            ?>
        </div>
    </div>

    <div class="fighting-container">

        <?php


        if (isset($_GET['player1']) &&  isset($_GET['player2'])) {

            if (isset($player1) && isset($player2)) {
                $player1->setPower($dice->launchDice());
                $player2->setPower($dice->launchDice());

                echo "<p><span class='perso1'>" . $player1->getNom() . "</span> possède " . $player1->getPV() . "points de vie et a " . $player1->getPower() . " power. <span class='perso2'>" . $player2->getNom() . "</span> possède " . $player2->getPV() . "points de vie et a " . $player2->getPower() . " de power.<br>";

                if ($player1->getPower() === $player2->getPower()) {
                    echo "Ils ont la même force. <br> Ils se serrent la main et vont boire un coup. <br> SUS A LA VIOLENCE !!! <img style='width:200px' src='https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Beer_mug_transparent.png/1024px-Beer_mug_transparent.png' alt='DRUNK MADAFAKA'/>";
                    // header('refresh:10;url=index.php');
                }
                if ($player1->getPower() > $player2->getPower()) {
                    echo "<br>";
                    echo "<span class='perso1'>" . $player1->getNom() . "</span> va attaquer en 1er <br>";

                    while ($player1->getPV() > 0 && $player2->getPV() > 0) {
                        echo p1first($dice, $player1, $player2);
                        echo p2first($dice, $player1, $player2);
                    }
                } elseif ($player1->getPower() < $player2->getPower()) {
                    echo "<br>";
                    echo "<span class='perso2'>" . $player2->getNom() . "</span> va attaquer en 1er <br>";

                    while ($player1->getPV() > 0 && $player2->getPV() > 0) {
                        echo p2first($dice, $player1, $player2);
                        echo p1first($dice, $player1, $player2);
                    }
                };

                if ($player1->getPV()  <= 0) {
                    $database->delete($player1->getNom());
                    echo "<span class='perso1'>" . $player1->getNom() . "</span> est mort(e) !</p>";
                    $database->update($player2->getNom(), $player2->getPV());
                };

                if ($player2->getPV() <= 0) {
                    $database->delete($player2->getNom());
                    echo "<span class='perso2'>" . $player2->getNom() . "</span> est mort(e) !</p>";
                    $database->update($player1->getNom(), $player1->getPV());
                };
            }
        }
        ?>


    </div>
</body>

</html>