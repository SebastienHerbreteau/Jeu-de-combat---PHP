<?php
class Player
{
    private string $nom;
    private INT $PV;
    private INT $power;
    private string $PA;

    public function __construct(string $nom, INT $PV, INT $power, INT $PA)
    {
        $this->nom = $nom;
        $this->PV = $PV;
        $this->power = $power;
        $this->PA = $PA;
    }

    public function attack($launch): int
    {
        return $this->power * $launch;
    }

    public function subirDegats($attaque): int
    {
        return $this->PV -= $attaque;
    }
    public function getNom()
    {
        return  $this->nom;
    }

    public function getPV()
    {
        return  $this->PV;
    }
    public function setPV($PV): int
    {
        return  $this->PV = $PV;
    }

    public function getPower()
    {
        return  $this->power;
    }

    public function setPower($power): int
    {
        return  $this->power = $power;
    }

    public function getPA()
    {
        return  $this->PA;
    }

    public function random()
    {
        $array = [
            "en lui mettant un gros coup dans les valseuses - COUP SPECIAL !!!!",
            "en lui rapant les couilles sur du papier de verre - C'est très efficace !!",
            "en lui pincant les tétons - Humm c'est agréable...",
            "en s'asseillant sur son visage - Oula ça manque d'hygiène par là...",
            "en lui faisant écouter l'intégrale de Michel Sardou - Ce n'est pas très efficace.",
            "parce qu'il/elle l'a bien mérité !",
        ];

        $result = rand(0, 5);
        return $array[$result];
    }
};
