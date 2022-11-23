<?php

class dice
{

    public function __construct()
    {
    }
    public function launchDice()
    {
        return rand(1, 6);
    }
}
