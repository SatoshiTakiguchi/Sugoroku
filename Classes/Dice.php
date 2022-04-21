<?php

class Dice{
    private $dice_range;

    public function __construct(
        $dice_range = 3
    ){
        $this->dice_range = $dice_range;   
    }

    public function diceRoll(){
        $num_list = range(1,$this->dice_range);
        $key = array_rand($num_list);
        return $num_list[$key];
    }

    public function setDiceRange($dice_range){
        $this->dice_range = $dice_range;
    }

}

?>