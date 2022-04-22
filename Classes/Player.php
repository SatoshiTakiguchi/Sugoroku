<?php

require_once 'Classes/Dice.php';
require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Item.php';
require_once 'Classes/Ivent.php';
require_once 'Classes/PlayerInput.php';

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
    //
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
    //
    // 行動リスト表示
    private function printActionList(){
        WaitProcessing::sleep(0.2);
        echo "0:サイコロを振る\n";
        WaitProcessing::sleep(0.2);
        echo "1:アイテムを使用する\n";
        WaitProcessing::sleep(0.2);
        echo "2:マップの表示\n";
        WaitProcessing::sleep(0.2);
    }

    // アイテムリスト表示
    private function printItemList(){
        echo "使用するアイテムを選んでください。\n";
        for($num = 0; $num < count($this->item_list); $num++){
            $item = $this->item_list[$num];
            echo "{$num}:{$item->getName()}";
            echo "(効果){$item->getIvent()}\n";
        }
        echo count($this->item_list),":行動選択にもどる\n";
    }

    // ゴールまでのマス数表示
    public function printToGoal($game){
        $goal_square = $game->getGoalSquare();
        echo "ゴールまで",$goal_square-$this->getPosition(),"マス\n";
        WaitProcessing::sleep(0.5);
    }

    // アイテム選択処理
    private function selectItemNumber(){
        while(true){
            $this->printItemList();
            $item_key = trim(fgets(STDIN));
            // 選択しなおしを返す
            if($item_key == count($this->item_list)){
                return $item_key;
            }

            if(!PlayerInput::inputCheck($this->item_list,$item_key)){continue;}
            
            // 確認して値を返す
            if(WaitProcessing::submit($item_key)){
                return (int)$item_key;        
            }
            continue;
        }
    }

    // 終了確認
    public function confirmEnd(){
        echo "エンターキーを押して",$this->name,"さんの番を終了する。\n";
        WaitProcessing::enter($this->isAuto);
    }

    // アクション関数
        // サイコロ
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
        // アイテム
        private function useItem($item_key,$game){
            $item = $this->item_list[(int)$item_key];
            echo $item->getName(),"を使った\n";
            Ivent::apply($game, $this, $item->getIvent());
            array_splice($this->item_list,$item_key,1);
        }
        // マップ確認
        private function printMap($game){
            $game->printBoardAndPlayerPosition();
            echo "エンターを押して行動選択に戻る\n";
            WaitProcessing::enter($this->isAuto);
        }
    //
    // 行動
    public function action($game){
        $player_list = $game->getPlayerList();
        $this->action_num += 1;
        while (true){
            echo $this->name,"さんの番 ";
            WaitProcessing::sleep(0.2);
            // ゴールまでのマス数
            $this->printToGoal($game);
            // 休み処理
            if($this->penalty_turn){
                echo "{$this->name}は{$this->penalty_turn}回休み\n";
                $this->penalty_turn -= 1;
                break;
            }
            // 行動リスト表示
            $this->printActionList();
            echo "番号を選んでエンターを押してください\n";
            // オート操作処理
            if($this->isAuto){
                WaitProcessing::sleep(0.5);
                $this->dice();
                break;   
            }
            // 行動
            $action_number = trim(fgets(STDIN));
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
                    $this->useItem($item_key,$game);
                    break 2;

                // マップ確認
                case 2:
                    $this->printMap($game);
                    continue 2;
                // 例外
                default:
                    echo "表示された番号を選んでください\n";
                //
            }
            // 
        }
    }
}

?>