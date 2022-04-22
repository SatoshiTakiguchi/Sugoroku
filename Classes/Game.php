<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Ivent.php';

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
        $this->square_list = $board->getBorad();
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
    // プレイヤー指定
    public function selectOtherPlayer($selecter_player){
        while(true){
            foreach($this->player_list as $key => $player){
                if($player == $selecter_player){continue;}
                echo $key,":",$player->getName(),"\n";
            }
            echo "指定する人を番号で選択してください\n";
            $selected_key = fgets(STDIN);

            if(!PlayerInput::inputCheck($this->player_list,$selected_key)){continue;}

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
        $i = 1;
        foreach($this->goal_players as $player){
            echo $i,"位:",$player->getName(),"\n行動回数:",$player->getActionNum(),"\n";
            $i+=1;
        }
    }

    // マップ表示
    public function printBoardAndPlayerPosition(){
        $all_player_list = array_merge($this->player_list, $this->goal_players);
        $player_position_list = [];
        foreach($all_player_list as $player){
            $player_position_list[$player->getName()] = $player->getPosition();
        }
        echo "-------マップ-------\n";
        WaitProcessing::sleep(0.1);
        foreach($this->square_list as $squere_number => $effect){
            WaitProcessing::sleep(0.1);
            echo $effect;
            if($mach_player_list = array_keys($player_position_list,$squere_number)){
                $str = implode(" ",$mach_player_list);
                print " ".$str;
            }
            echo "\n";
        }
        echo "\n";
    }

    // スタート
    public function start(){
        echo "ゲームを開始します\n";
        WaitProcessing::sleep(0.5);
        echo "今回のマップは\n";
        WaitProcessing::sleep(0.5);
        $this->printBoardAndPlayerPosition();
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
                $player->action($this);

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
                Ivent::apply($this,$player,$square_effect);

                // ゴール判定
                $position = $player->getPosition();
                if($this->goal_square <= $position){
                    $this->resultProcessiong($player);
                    echo "\n";
                    continue;
                }

                // ゴールまでいくつか
                $player->printToGoal($this);
                echo "\n";
                $player->confirmEnd();
            }
        }
        $this->printResult();
    }
}

?>