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
        $goal_position = count($this->board->getBorad());
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

    public function start(){
        $square_list = $this->board->getBorad();
        // ゴール位置取得
        $goal_position = count($this->board->getBorad());
        while($this->player_list){
            foreach($this->player_list as $player){
                if($player->getPenaltyTurn()){
                    $player->action();
                    echo "\n";
                    continue;
                }
                $player->action();
                $position = $player->getPosition();

                // ゴール判定
                if($goal_position <= $position){
                    $this->resultProcessiong($player);
                    echo "\n";
                    continue;
                }

                $square = $square_list[$position];
                Ivent::apply($square);

                // ゴール判定
                if($goal_position <= $position){
                    $this->resultProcessiong($player);
                    echo "\n";
                    continue;
                }

                echo "ゴールまで",$goal_position-$position,"マス\n";
                WaitProcessing::sleep(0.5);
                echo "\n";
            }
        }
        $this->printResult();
    }
}

?>