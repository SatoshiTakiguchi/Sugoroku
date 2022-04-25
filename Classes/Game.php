<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Event.php';

class Game{
    private $square_list;
    private $goal_square;
    private $player_list = [];
    private $goal_players = [];

    // ゲーム準備
    public function addPlayer($player){
        $this->player_list[] = $player;
    }
    public function setBoard($board){
        $this->square_list = $board->getBoard();
        $this->goal_square = count($this->square_list) - 1;
    }

    // データ取得関数
        public function getGoalSquare(){
            return $this->goal_square;
        }
        public function getPlayerList(){
            return $this->player_list;
        }
        public function getGoalPlayers(){
            return $this->goal_players;
        }
        public function getSquareList(){
            return $this->square_list;
        }
    // 
    // セーブロード
    public function saveGame(){

    }
    //
    // プレイヤー指定
    public function selectOtherPlayer($selecter_player){
        while(true){
            foreach($this->player_list as $key => $player){
                if($player == $selecter_player){continue;}
                echo $key,":",$player->getName(),"\n";
            }
            echo "指定する人を番号で選択してください\n";
            $selected_key = fgets(STDIN);

            // 選択の整合性確認
            if(!PlayerInput::inputCheck($this->player_list,$selected_key)){continue;}

            // プレイヤー確認
            $selected_key = (int)$selected_key;
            if(WaitProcessing::submit($this->player_list[$selected_key]->getName()."さん")){
                return $this->player_list[$selected_key];
            }
        }
    }
    // 結果入力
    private function resultProcessiong($player){
        $this->goal_players[] = $player;
        $player->setPosition($this->goal_square);
        echo "{$player->getName()}はゴールした\n";
        // player_listから削除
        array_splice(
            $this->player_list,
            array_keys($this->player_list,$player)[0],
            1
        );
    }

    // 結果出力
    private function printResult(){
        echo "全員がゴールした\n";
        WaitProcessing::sleep(1);
        echo "点数発表\n";
        WaitProcessing::sleep(2);
        // 点数計算
        $last_goal_turn = end($this->goal_players)->getActionNum();
        foreach($this->goal_players as $player){
            $bonus_point = $last_goal_turn - $player->getActionNum();
            $player->addVictoryPoint($bonus_point);

            $item_point = count($player->getItemList());
            $player->addVictoryPoint($item_point);

            echo $player->getName(),"さん\n";
            WaitProcessing::sleep(1);
            echo "持ち点",$player->getVictoryPoint(),"点\n";
            WaitProcessing::sleep(1);
            echo "行動回数ボーナス",$bonus_point,"点\n";
            WaitProcessing::sleep(1);
            echo "アイテム未使用ボーナス",$item_point,"点\n";
            WaitProcessing::sleep(1.5);
            echo "合計",$player->getVictoryPoint(),"点\n";
            echo "\n";
        }
        
        // 並び替え
        $positions = array_column($this->goal_players,'victory_point');
        array_multisort($positions, SORT_DESC, $this->goal_players);
        
        //
        foreach($this->goal_players as $key => $player){
            WaitProcessing::sleep(1);
            echo $key+1,"位:",$player->name,"さん ",$player->victory_point,"\n";
        }
    }

    // ポジションリスト
    public function createPositionList(){
        $all_player_list = array_merge($this->player_list, $this->goal_players);
        $player_position_list = [];
        foreach($all_player_list as $player){
            $player_position_list[$player->getName()] = $player->getPosition();
        }
        return $player_position_list;
    }
    // 全体マップ表示
    public function printAllMap(){
        $player_position_list = $this->createPositionList();
        echo "-------全体マップ-------\n";
        WaitProcessing::sleep(0.1);
        foreach($this->square_list as $squere_number => $effect){
            WaitProcessing::sleep(0.1);
            echo $effect;
            if($mach_player_list = array_keys($player_position_list,$squere_number)){
                $name = implode(" ",$mach_player_list);
                print " ".$name;
            }
            echo "\n-----------------------\n";
        }
        echo "\n";
    }
    // 部分マップ表示
    public function printPartOfMap($player_position, $end_number = 10){
        $player_position_list = $this->createPositionList();
        $end_number = $end_number + $player_position;
        echo "-------周辺マップ-------\n";
        for($i = $player_position; $i < $end_number; $i++){

            if($i > $this->goal_square){break;}

            WaitProcessing::sleep(0.1);
            echo $this->square_list[$i];
            if($mach_player_list = array_keys($player_position_list,$i)){
                $name = implode(" ",$mach_player_list);
                print " ".$name;
            }
            echo "\n-----------------------\n";
        }
        echo "\n";

    }
    // 勝利点一覧
    public function printVictoryPoint(){
        foreach($this->player_list as $player){
            echo $player->getName(),"さん:",$player->getVictoryPoint(),"点\n";
        }
        echo "\n";
    }

    // スタート
    public function start(){
        echo "ゲームを開始します\n";
        WaitProcessing::sleep(0.5);
        echo "今回のマップは\n";
        WaitProcessing::sleep(0.5);
        $this->printAllMap();

        while($this->player_list){
            foreach($this->player_list as $player){
                // 休み処理
                if($player->getPenaltyTurn()){
                    $player->action($this);
                    echo "\n";
                    $player->confirmEnd();
                    continue;
                }
                //行動
                if($player->action($this)){
                    // ゴールまでいくつか
                    $player->printToGoal($this);
                    echo "\n";
                    $player->confirmEnd();
                    continue;
                }

                // ゴール判定
                $position = $player->getPosition();
                if($this->goal_square <= $position){
                    $this->resultProcessiong($player);
                    echo "\n";
                    continue;
                }

                // 止まったマス処理
                $square_effect = $this->square_list[$position];
                echo $square_effect,"マスに止まった\n";
                Event::apply($this,$player,$square_effect);

                // ゴール判定
                $position = $player->getPosition();
                if($this->goal_square <= $position){
                    $this->resultProcessiong($player);
                    echo "\n";
                    continue;
                }

                // ゴールまでいくつか
                echo $player->name,"さん、ゴールまで";
                $player->printToGoal($this);
                echo "\n";
                $player->confirmEnd();
            }
        }
        WaitProcessing::sleep(2);
        $this->printResult();
    }
}

?>