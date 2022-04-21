<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Ivent.php';

class Game{
    private $board_instance;
    private $square_list;
    private $player_list = [];
    private $goal_players = [];

    public function addPlayer($player){
        $this->player_list[] = $player;
    }
    public function setBoard($board){
        $this->board_instance = $board;
        $this->square_list = $board->getBorad();
    }

    // 結果入力
    private function resultProcessiong($player){
        $this->goal_players[] = $player;
        $goal_position = count($this->square_list);
        $player->setPosition($goal_position);
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

    //
    private function printToGoal($player){
        $goal_position = count($this->square_list) - 1;
        echo "ゴールまで",$goal_position-$player->getPosition(),"マス\n";
        WaitProcessing::sleep(0.5);
    }

    public function start(){
        // ゴール位置取得
        $goal_position = count($this->square_list) - 1;
        while($this->player_list){
            foreach($this->player_list as $player){
                echo $player->getName(),"の番\n";
                $this->printToGoal($player);
                // 休み処理
                if($player->getPenaltyTurn()){
                    $player->action();
                    echo "\n";
                    continue;
                }
                //行動
                $player->action();

                // ゴール判定
                $position = $player->getPosition();
                if($goal_position <= $position){
                    $this->resultProcessiong($player);
                    echo "\n";
                    continue;
                }

                // 止まったマス処理
                $square = $this->square_list[$position];
                Ivent::apply($player,$square);

                // ゴール判定
                $position = $player->getPosition();
                if($goal_position <= $position){
                    $this->resultProcessiong($player);
                    echo "\n";
                    continue;
                }

                $this->printToGoal($player);
                echo "\n";
            }
        }
        $this->printResult();
    }
}

?>