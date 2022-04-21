<?php

class Dice{

    public function diceRoll(){
        $num_list = range(1,3);
        $key = array_rand($num_list);
        return $num_list[$key];
    }

}

?>