<?php

function p1first($dice, $player1, $player2)
{

    $launch = $dice->launchDice();
    $attaque = $player1->attack($launch);
    $player2->setPV($player2->subirDegats($attaque));
    $str  = "<span class='perso1'>" . $player1->getNom() . "</span> --> Lance le dé et obtient $launch <br>";
    $str .= "<span class='perso1'>" . $player1->getNom() . "</span> --> Inflige $attaque de dégats à <span class='perso2'>" . $player2->getNom() . " </span> " . $player2->random() . " <br>";
    $str .= "<span class='perso2'>" . $player2->getNom() . "</span> descend à " . $player2->getPV() . " PV <br><br>";

    return $str;
}

function p2first($dice, $player1, $player2)
{
    $launch = $dice->launchDice();
    $attaque = $player2->attack($launch);
    $player1->setPV($player1->subirDegats($attaque));
    $str  = "<span class='perso2'>" . $player2->getNom() . "</span> --> Lance le dé et obtient $launch <br>";
    $str .= "<span class='perso2'>" . $player2->getNom() . "</span> --> Inflige $attaque de dégats à <span class='perso1'>" . $player1->getNom() . " </span> " . $player2->random() . " <br>";
    $str .= "<span class='perso1'>" . $player1->getNom() . "</span> descend à " . $player1->getPV() . " PV <br><br>";

    return $str;
}
