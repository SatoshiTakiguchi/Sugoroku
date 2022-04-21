<?php

require_once 'Classes/Dice.php';

class Player{
    private $name;
    private $dice;
    private $position;
    private $action_num;

    public function __construct($name)
    {
        $this->name = $name;
        $this->dice = new Dice();
        $this->position = 0;
        $this->action_num = 0;
    }

    // データ取得
    public function getName(){
        return $this->name;
    }
    public function getDice(){
        return $this->dice;
    }
    public function getPosition(){
        return $this->position;
    }
    public function getActionNum(){
        return $this->action_num;
    }

    public function addActionNum(){
        $this->action_num += 1;
    }

    public function setPosition($num){
        $this->position = $num;
    }

    public function setDice($dice){
        $this->dice = $dice;
    }

    public function action(){
        $this->diceRoll();
    }

    public function diceRoll(){
        $dice_res = $this->dice->diceRoll();
        echo $this->name,"の番\n";
        // sleep(1);
        echo $dice_res,"が出た\n";
        // sleep(1);
        $this->position += $dice_res;
    }

}

?>