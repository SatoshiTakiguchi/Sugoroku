<?php

class Player{
    private $name;
    public  $dice;

    public function __construct($name)
    {
        $this->name = $name;   
    }

    // データ取得
    public function getName(){
        return $this->name;
    }
    public function getDice(){
        return $this->dice;
    }

    public function addDice($dice){
        $this->dice = $dice;
    }

}

?>