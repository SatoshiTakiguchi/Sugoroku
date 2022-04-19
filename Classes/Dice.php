<?php

class Dice{

    public function diceRoll(){
        return array_rand(range(1,3));
    }

}

?>