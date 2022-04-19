<?php

require_once 'Classes/Dice.php';

class Game{
    private $board;
    private $player_list = [];
    private $goal_players = [];

    public function addPlayer($player){
        $player->addDice(new Dice());
        $this->player_list[] = [
            'object'   => $player, 
            'position' => 0
        ];
    }

    // 結果入力
    public function inputResult(&$player_data){
        $goal = count($this->board->getBorad());
        $player_data['position'] = $goal;
        $this->goal_players[] = $player_data;
        // player_listから削除
        array_splice(
            $this->player_list,
            array_keys($this->player_list,$player_data)[0],
            1
        );
    }

    // 結果出力
    public function printResult(){
        $i = 1;
        foreach($this->goal_players as $player_data){
            $object = $player_data["object"];
            echo $i,"位：",$object->getName(),"\n";
            $i+=1;
        }
    }

    public function start(){
        $goal = count($this->board->getBorad());
        while($this->player_list){
            foreach($this->player_list as &$player_data){
                $dice         = $player_data['object']->getDice();
                $dice_res     = $dice->diceRoll();
                $position_now = $player_data['position'];

                //ゴール処理
                if($goal <= $position_now + $dice_res){
                    $this->inputResult($player_data);
                    continue;
                }

                //場所更新
                $player_data['position'] += $dice_res;
            }
        }
        $this->printResult();
    }

    public function setBoard($board){
        $this->board = $board;
    }

}

?>