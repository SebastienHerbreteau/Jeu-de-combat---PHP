<?php
// REQUIRE CLASSES
require_once("class/Database.class.php");
require_once("class/Personnage.class.php");
require_once("class/Dice.class.php");
// DATABASE CONNEXION
$database = new Database("localhost", "combat", "root", "");
$database->connect();
$database->prepReq("SELECT * FROM personnage");
$listOfCharacters = $database->fetchData();
@$newPlayer = $_GET['new-player'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de combat</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <form method="get" action="index.php">
        <input type="text" name="new-player">
        <input type="submit" value="Créer ton personnage">
        <?php
        $database->prepReq("INSERT INTO personnage (nom, PV, power, PA) VALUES $newPlayer'(',100, 0, 0)");
        ?>
        <!--         if (isset($_newPlayer)) {
            $database->prepReq("INSERT INTO personnage (nom, PV, power, PA) VALUES ('$newPlayer',100, 0, 0)");
        } -->
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
            <?php
            foreach ($listOfCharacters as $character) {
                echo "<option name='" . $character[1] . "'>$character[1]</option>";
            };
            ?>
        </select>
        <input type='submit' value='Select CHARACTERS and fight!'>
    </form>
    <div class="fighting">

        <div class="fighter-left">
            <h2>PLAYER</h2>
            <?= "<h3 class='style-player'>" . @$_GET['player'] . "</h3>"; ?>
            <?php
            $playerInfos = $database->prepReq("SELECT * FROM personnage WHERE nom LIKE '" . @$_GET['player'] . "'");
            $playerInfos = $database->fetchData();
            foreach ($playerInfos as $infoPlayer) {
            }
            ?>
            <?php
            ?>
        </div>
        <img class="vs" src="assets/img/versus.png">
        <div class="fighter-right">
            <h2>ENEMY</h2>
            <?php echo "<h3 class='style-enemy'>" . @$_GET['enemy'] . "</h3>"; ?>
            <?php
            $enemyInfos = $database->prepReq("SELECT * FROM personnage WHERE nom LIKE '" . @$_GET['enemy'] . "'");
            $enemyInfos = $database->fetchData();
            foreach ($enemyInfos as $infoEnemy) {
            }
            ?>
            <?php
            $dice = new Dice(1);
            $random = $dice->launchDice();
            ?>
        </div>

    </div>

    <?php
    // DECLARATION GLOBALE DES INFOS
    $player = $GLOBALS['infoPlayer'][1];
    $playerPV = $GLOBALS['infoPlayer'][2];
    $playerPower = $GLOBALS['infoPlayer'][3];
    $enemy = $GLOBALS['infoEnemy'][1];
    $enemyPV = $GLOBALS['infoEnemy'][2];
    $enemyPower = $GLOBALS['infoEnemy'][3];
    $playerPower = $dice->launchDice();
    $enemyPower = $dice->launchDice();
    ?>
    <div class="fighting-container">

        <?php


        if (isset($_GET['player']) &&  isset($_GET['enemy'])) {

            echo "$player possède $playerPV points de vie et a $playerPower de power. $enemy possède $enemyPV points de vie et a $enemyPower de power.<br>";
            if ($playerPower === $enemyPower) {
                echo "Ils ont la même force. <br> Ils se serrent la main et vont boire un coup. <br> SUS A LA VIOLENCE !!! <img style='width:200px' src='https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Beer_mug_transparent.png/1024px-Beer_mug_transparent.png' alt='DRUNK MADAFAKA'/>";
                header('refresh:10;url=index.php');
            }
            if ($playerPower > $enemyPower) {

                echo "<br>";
                echo $player . " va attaquer en 1er <br>";
                while ($playerPV > 0 && $enemyPV > 0) {
                    // attaque du player
                    $launch = $dice->launchDice();
                    $point = $playerPower * $launch;
                    $enemyPV -= $point;
                    echo "$player lance le dé et obtient $launch <br>";
                    echo "$player inflige $point de dégats à $enemy <br>";
                    echo "$enemy a $enemyPV PV <br>";
                    // riposte de l'enemy
                    $launch = $dice->launchDice();
                    $point = $enemyPower * $launch;
                    $playerPV -= $point;
                    echo "$enemy lance le dé et obtient $launch <br>";
                    echo "$enemy inflige $point de dégats à $player <br>";
                    echo "$player a $playerPV PV <br>";
                }
            } elseif ($playerPower < $enemyPower) {

                echo "<br>";
                echo $enemy . " va attaquer en 1er <br>";
                while ($playerPV > 0 && $enemyPV > 0) {
                    $launch = $dice->launchDice();
                    $point = $enemyPower * $launch;
                    $playerPV -= $point;
                    echo "$enemy lance le dé et obtient $launch <br>";
                    echo "$enemy inflige $point de dégats à $player <br>";
                    echo "$player a $playerPV PV <br>";

                    $launch = $dice->launchDice();
                    $point = $playerPower * $launch;
                    $enemyPV -= $point;
                    echo "$player lance le dé et obtient $launch <br>";
                    echo "$player inflige $point de dégats à $enemy <br>";
                    echo "$enemy a $enemyPV PV <br>";
                }
            };

            if ($playerPv <= 0) {
                $database->prepReq("DELETE FROM personnage WHERE nom = '$player' ");
                echo "$player est mort !";
            } else if ($enemyPv <= 0) {
                $database->prepReq("DELETE FROM personnage WHERE nom = '$enemy' ");
                echo "$enemy est mort !";
            }
        }
        ?>


    </div>
</body>

</html>