<?php

require_once 'Classes/Dice.php';
require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Item.php';
require_once 'Classes/Ivent.php';

class Player{
    private $name;
    private $dice;
    private $position;
    private $action_num;
    private $isAuto;
    private $penalty_turn;
    private $item_list;

    public function __construct(
        $name,
        $isAuto = false
    ){
        $this->name = $name;
        $this->dice = new Dice();
        $this->position = 0;
        $this->action_num = 0;
        $this->isAuto = $isAuto;
        $this->penalty_turn = 0;
        $this->item_list = [];
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
        public function getPenaltyTurn(){
            return $this->penalty_turn;
        }
        public function getItemList(){
            return $this->item_list;
        }

    // データ変更関数
        public function setPosition($num){
            $this->position = $num;
        }
        public function setDice($dice){
            $this->dice = $dice;
        }
        public function addPenaltyTurn($turn){
            $this->penalty_turn += $turn;
        }
        public function addActionNum(){
            $this->action_num += 1;
        }
        public function addPosition($number){
            $this->position += $number;
            if($this->position < 0){
                $this->position = 0;
            }
        }
        public function addItem($item){
            $this->item_list[] = $item;
        }

    public function useItem($item,$player_list){
        Ivent::apply($player_list,$this,$item->getIvent());
    }

    public function action(){
        $this->action_num += 1;
        if(!$this->isAuto){
            WaitProcessing::enter();
        }
        // 休み処理
        if($this->penalty_turn){
            echo "{$this->name}は{$this->penalty_turn}回休み\n";
            $this->penalty_turn -= 1;
            WaitProcessing::sleep(0.5);
            return;
        }

        $this->dice();
    }

    public function dice(){
        echo "サイコロを振った。\n";
        WaitProcessing::sleep(0.5);
        $dice_res = $this->dice->diceRoll();
        echo $dice_res,"が出た！\n";
        WaitProcessing::sleep(0.5);
        echo $dice_res,"進む。\n";
        WaitProcessing::sleep(0.5);
        $this->addPosition($dice_res);
    }

}

?>