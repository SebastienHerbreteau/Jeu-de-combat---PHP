<?php

function p1first($dice, $personnage1, $personnage2)
{

    $launch = $dice->launchDice();
    $attaque = $personnage1->attack($launch);
    $personnage2->PV = $personnage2->subirDegats($attaque);
    $str  = "<span class='perso1'>$personnage1->nom</span> --> Lance le dé et obtient $launch <br>";
    $str .= "<span class='perso1'>$personnage1->nom</span> --> Inflige $attaque de dégats à <span class='perso2'>$personnage2->nom</span> <br>";
    $str .= "<span class='perso2'>$personnage2->nom</span> descend à $personnage2->PV PV <br><br>";

    return $str;
}

function p2first($dice, $personnage1, $personnage2)
{
    $launch = $dice->launchDice();
    $attaque = $personnage2->attack($launch);
    $personnage1->PV = $personnage1->subirDegats($attaque);
    $str  = "<span class='perso2'>$personnage2->nom</span> --> Lance le dé et obtient $launch <br>";
    $str .= "<span class='perso2'>$personnage2->nom</span> --> Inflige $attaque de dégats à <span class='perso1'>$personnage1->nom</span> <br>";
    $str .= "<span class='perso1'>$personnage1->nom</span> descend à $personnage1->PV PV <br><br>";

    return $str;
}
