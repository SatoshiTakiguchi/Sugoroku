<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Item.php';

class Event{

    public static function apply($game,$player,$square_effect){
        // 単純移動
        if($square_effect == "すすむ"){
            echo "サイコロの数だけ追加で進める\n";
            WaitProcessing::sleep(0.5);
            echo "エンターを押してサイコロを振ってください\n";
            WaitProcessing::enter($player->isAuto);
            $number = $player->dice->diceRoll();
            Event::addPlayerPosition($player, $number);
        }
        elseif($square_effect == "もどる"){
            echo "サイコロの数だけ戻らなければならない\n";
            WaitProcessing::sleep(0.5);
            echo "エンターを押してサイコロを振ってください\n";
            WaitProcessing::enter($player->isAuto);
            $number = $player->dice->diceRoll();
            Event::reducePlayerPosition($player, $number);
        }
        // 休みマス
        elseif($square_effect == "休み"){
            $number = 1;
            echo "1回休み\n";
            Event::addPrenaltyTurn($player, $number);
        }
        // ポイント
        elseif($square_effect == "ポイント"){
            echo "サイコロの数だけポイントを獲得できる\n";
            WaitProcessing::sleep(0.5);
            echo "エンターを押してサイコロを振ってください\n";
            WaitProcessing::enter($player->isAuto);
            $number = $player->dice->diceRoll();
            Event::addVictoryPoint($player,$number);
            // アイテムの場合ターン終了
            return true;
        }

        // アイテムマス
        elseif($square_effect == "アイテム"){
            Event::recieveItem($player);
            return false;
        }
        // プレイヤー指定アイテム(今のところ休ませるのみ)
        elseif(preg_match("/指定した人/",$square_effect)){
            $target_player = ($game->selectOtherPlayer($player));
            preg_match("/[0-9]+ターン休/", $square_effect, $penalty_turn_str);
            $penalty_turn = (int)str_replace("ターン休","",$penalty_turn_str[0]);
            Event::addPrenaltyTurn($target_player, $penalty_turn);
            // 使用後ターンを終えるかどうか
            return true;
        }

        // アイテムのためにまだ必要
            // すすむマス
            elseif(preg_match("/[0-9]+マスすすむ/",$square_effect)){
                // 数値抽出
                $number = (int)str_replace("マスすすむ","",$square_effect);
                Event::addPlayerPosition($player,$number);
            }
            // もどるマス
            elseif(preg_match("/[0-9]+マスもどる/",$square_effect)){
                // 数値抽出
                $number = (int)str_replace("マス戻る","",$square_effect);
                Event::reducePlayerPosition($player,$number);
            }
            // ペナルティターンマス
            elseif(preg_match("/[0-9]+ターンやすみ/",$square_effect)){
                $number = (int)str_replace("ターンやすみ","",$square_effect);
                Event::addPrenaltyTurn($player,$number);
            }
            // ポイント獲得
            elseif(preg_match("/[0-9]+ポイント獲得/",$square_effect)){
                $number = (int)str_replace("ポイント獲得","",$square_effect);
                Event::addVictoryPoint($player,$number);
                // アイテムの場合ターン終了
                return true;
            }
        //
        // 何もなしマス（例外なマスも）
        else{
            Event::nothing();
        }
        
    }

    // 何もなし
    public static function nothing(){
        echo "何もなし\n";
    }
    // アイテム
    public static function recieveItem($player){
        WaitProcessing::sleep(0.5);
        $item = new Item();
        $player->addItem($item);
        echo $item->getName(),"を獲得した！\n";
        WaitProcessing::sleep(0.4);
        echo "使うと",$item->getEvent(),"効果がある\n";
        WaitProcessing::sleep(0.4);
    }
    // 単純移動
    public static function addPlayerPosition($player,$number){
        WaitProcessing::sleep(0.5);
        echo $number,"マスすすむ。\n";
        $player->addPosition($number);
    }
    public static function reducePlayerPosition($player,$number){
        WaitProcessing::sleep(0.5);
        echo $number,"マスもどる。\n";
        $player->addPosition(-$number);
    }
    // ペナルティターン
    public static function addPrenaltyTurn($player,$penalty_turn){
        WaitProcessing::sleep(0.5);
        echo $player->getName(),"さんは",$penalty_turn,"ターン動けなくなった。\n";
        $player->addPenaltyTurn($penalty_turn);
    }
    // ポイント獲得
    public static function addVictoryPoint($player,$number){
        WaitProcessing::sleep(0.5);
        echo $player->getName(),"さんは",$number,"ポイント獲得\n";
        $player->addVictoryPoint($number);
    }
}

?>