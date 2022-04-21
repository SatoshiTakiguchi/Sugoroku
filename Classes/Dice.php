<?php

class Dice{
    private $number_list;  // 出目のリスト
    private $number_range; // 初期サイコロの出目

    public function __construct(
        $number_range = 3
    ){
        $this->number_range = $number_range;
        $this->number_list = range(1,$this->number_range);   
    }

    public function diceRoll(){
        $key = array_rand($this->number_list);
        $this->number_list = range(1,$this->number_range);
        return $this->number_list[$key];
    }

    public function setDiceList($number_list){
        $this->number_list = $number_list;
    }

}

?>