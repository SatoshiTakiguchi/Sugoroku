<?php

require_once 'Classes/Dice.php';
require_once 'Classes/WaitProcessing.php';

class Player{
    private $name;
    private $dice;
    private $position;
    private $action_num;
    private $isAuto;

    public function __construct(
        $name,
        $isAuto = false
    ){
        $this->name = $name;
        $this->dice = new Dice();
        $this->position = 0;
        $this->action_num = 0;
        $this->isAuto = $isAuto;
    }

    // データ取得関数
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

    // データ入力関数
        public function setPosition($num){
            $this->position = $num;
        }
        public function setDice($dice){
            $this->dice = $dice;
        }

    //
    public function addActionNum(){
        $this->action_num += 1;
    }

    public function action(){
        echo $this->name,"の番\n";
        if(!$this->isAuto){
            WaitProcessing::enter();
        }
        $this->diceRoll();
    }

    public function diceRoll(){
        $dice_res = $this->dice->diceRoll();
        echo $dice_res,"が出た！\n";
        WaitProcessing::sleep(0.5);
        echo $dice_res,"進む。\n";
        WaitProcessing::sleep(0.5);
        $this->position += $dice_res;
    }

}

?>