<?php
// REQUIRE CLASSES
require_once("class/Database.class.php");
require_once("class/Personnage.class.php");
require_once("class/Dice.class.php");
require_once("function.php");
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

    <form method="get" action="index.php">
        <input type="text" name="new-player">
        <input type="submit" value="Créer ton personnage">
        <?php
        if (isset($newPlayer)) {
            $database->create($newPlayer);
        }

        ?>
    </form>
    <p class="or">OR</p>

    <form method="get" action="index.php">
        <select name="player" id="select-player">
            <option value="">--Select Player--</option>
            <?php
            foreach ($listOfCharacters as $character) {
                echo "<option name='" . $character[1] . "'>$character[1]</option>";
            };
            ?>
        </select>
        <select name="enemy" id="select-enemy">
            <option value="">--Select Enemy--</option>
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
            if (isset($_GET['player'])) :
                echo "<h3 class='style-player'>" . $_GET['player'] . "</h3>";
                $playerInfos = $database->read($_GET['player']);
                $playerInfos = $database->fetchData();
                foreach ($playerInfos as $infoPlayer) {
                    $player = $infoPlayer[1];
                    $playerPV = $infoPlayer[2];
                    $playerPower = $dice->launchDice();
                    $personnage1 = new Personnage($player, $playerPV, $playerPower, 0);
                }
            endif;
            ?>
        </div>
        <img class="vs" src="assets/img/versus.png">
        <div class="fighter-right">

            <!----voir toLowerCase()- - strtolower(sttring)---->
            <h2>PLAYER 2</h2>
            <?php if (isset($_GET['enemy'])) :
                echo "<h3 class='style-enemy'>" . $_GET['enemy'] . "</h3>";
                $enemyInfos = $database->read($_GET['player']);
                $enemyInfos = $database->fetchData();
                foreach ($enemyInfos as $infoEnemy) {
                    $enemy = $infoEnemy[1];
                    $enemyPV = $infoEnemy[2];
                    $enemyPower = $dice->launchDice();
                    $personnage2 = new Personnage($enemy, $enemyPV, $enemyPower, 0);
                }
            endif;
            ?>
        </div>
    </div>

    <div class="fighting-container">

        <?php


        if (isset($_GET['player']) &&  isset($_GET['enemy'])) {
            $personnage1->power = $dice->launchDice();
            $personnage2->power = $dice->launchDice();
            echo "<span class='perso1'>$personnage1->nom</span> possède $personnage1->PV points de vie et a $personnage1->power de power. <span class='perso2'>$personnage2->nom</span> possède $personnage2->PV points de vie et a $personnage2->power de power.<br>";

            if ($personnage1->power === $personnage2->power) {
                echo "Ils ont la même force. <br> Ils se serrent la main et vont boire un coup. <br> SUS A LA VIOLENCE !!! <img style='width:200px' src='https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Beer_mug_transparent.png/1024px-Beer_mug_transparent.png' alt='DRUNK MADAFAKA'/>";
                header('refresh:10;url=index.php');
            }
            if ($personnage1->power > $personnage2->power) {

                echo "<br>";
                echo "<span class='perso1'>" . $personnage1->nom . "</span> va attaquer en 1er <br>";
                while ($personnage1->PV > 0 && $personnage2->PV > 0) {

                    echo p1first($dice, $personnage1, $personnage2);
                    echo p2first($dice, $personnage1, $personnage2);
                }
            } elseif ($personnage1->power < $personnage2->power) {

                echo "<br>";
                echo "<span class='perso2'>" . $personnage2->nom . "</span> va attaquer en 1er <br>";
                while ($personnage1->PV > 0 && $personnage2->PV > 0) {

                    echo p2first($dice, $personnage1, $personnage2);
                    echo p1first($dice, $personnage1, $personnage2);
                }
            };
            if ($personnage1->PV  <= 0) {
                $database->delete("$personnage1->nom");
                echo "<span class='perso1'>$personnage1->nom</span> est mort(e) !";
            };

            if ($personnage2->PV <= 0) {
                $database->delete("$personnage2->nom");
                echo "<span class='perso2'>$personnage2->nom</span> est mort(e) !";
            };
        }
        ?>


    </div>
</body>

</html>