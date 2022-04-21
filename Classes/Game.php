<?php

class Game{
    private $board;
    private $player_list = [];
    private $goal_players = [];

    public function addPlayer($player){
        $this->player_list[] = $player;
    }
    public function setBoard($board){
        $this->board = $board;
    }

    // 結果入力
    public function inputResult(&$player){
        $goal = count($this->board->getBorad());
        $player->setPosition($goal);
        $this->goal_players[] = $player;
    }

    // 結果出力
    public function printResult(){
        $i = 1;
        foreach($this->goal_players as $player){
            echo $i,"位:",$player->getName(),"\n行動回数:",$player->getActionNum(),"\n";
            $i+=1;
        }
    }

    public function start(){
        $goal = count($this->board->getBorad());
        while($this->player_list){
            foreach($this->player_list as $player){
                $player->addActionNum();
                $player->action();
                $position = $player->getPosition();

                // ゴール処理
                if($goal <= $position){
                    echo "{$player->getName()}はゴールした\n";
                    $this->inputResult($player);
                    // player_listから削除
                    array_splice(
                        $this->player_list,
                        array_keys($this->player_list,$player)[0],
                        1
                    );
                    echo "\n";
                    continue;
                }
                echo "ゴールまで",$goal-$position,"マス\n";
                echo "\n";
            }
        }
        $this->printResult();
    }

}

?>