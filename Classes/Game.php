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

    // スタート
    public function start(){
        while($this->player_list){
            foreach($this->player_list as $player){
                echo $player->getName(),"の番\n";
                $player->printToGoal($this);
                // 休み処理
                if($player->getPenaltyTurn()){
                    $player->action();
                    echo "\n";
                    continue;
                }
                //行動
                $player->action($this->player_list);

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
            }
        }
        $this->printResult();
    }
}

?>