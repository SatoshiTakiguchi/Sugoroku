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

    private function printActionList(){
        echo "0:サイコロを振る\n";
        echo "1:アイテムを使用する\n";
    }

    private function printItemList(){
        echo "\n使用するアイテムを選んでください。\n";
        for($num = 0; $num < count($this->item_list); $num++){
            $item = $this->item_list[$num];
            echo "{$num}:{$item->getName()}";
            echo "(効果){$item->getIvent()}\n";
        }
        echo count($this->item_list),":行動選択にもどる\n";
    }

    private function selectItemNumber(){
        while(true){
            $this->printItemList();
            $item_key = fgets(STDIN);
            // 選択しなおしを返す
            if($item_key == count($this->item_list)){
                return $item_key;
            }

            // 確認して値を返す
            if($item_key < count($this->item_list)){
                if(WaitProcessing::submit($item_key)){
                    return $item_key;        
                }
                continue;
            }

            WaitProcessing::sleep(0.2);
            echo "指定された数字を入力してください。\n";
        }
    }

    private function useItem($item_key,$player_list){
        $item = $this->item_list[(int)$item_key];
        echo $item->getName(),"を使った\n";
        Ivent::apply($player_list, $this, $item->getIvent());
        array_splice($this->item_list,$item_key,1);
    }

    public function action($player_list){
        $this->action_num += 1;
        // 休み処理
        if($this->penalty_turn){
            echo "{$this->name}は{$this->penalty_turn}回休み\n";
            $this->penalty_turn -= 1;
            WaitProcessing::sleep(0.5);
            return;
        }
        // 行動リスト表示
        while (true){
            $this->printActionList();
            if(!$this->isAuto){
                $action_number = fgets(STDIN);   
            }else{
                $this->dice();
                break;
            }
            switch ($action_number){
                // サイコロ
                case 0:
                    if(WaitProcessing::submit($action_number)){
                        $this->dice();
                        break 2;
                    }
                    continue 2;
                // アイテム
                case 1:
                    $item_key = $this->selectItemNumber();
                    // 行動選択し直す
                    if ($item_key == count($this->item_list)){
                        continue 2;
                    }
                    // アイテム使用
                    $this->useItem($item_key,$player_list);
                    break 2;

            }
        }
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