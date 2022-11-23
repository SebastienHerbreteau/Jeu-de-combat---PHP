<?php
class Personnage
{
    public string $nom;
    public INT $PV;
    public INT $power;
    public string $PA;

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
}
