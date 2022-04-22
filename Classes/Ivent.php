<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Item.php';

class Ivent{

    public static function apply($game,$player,$square_effect){
        // 
        if(preg_match("/指定した人/",$square_effect)){
            $target_player = ($game->selectOtherPlayer($player));
            preg_match("/[0-9]+ターン休/", $square_effect, $penalty_turn_str);
            $penalty_turn = (int)str_replace("ターン休","",$penalty_turn_str[0]);
            Ivent::addPrenaltyTurn($target_player, $penalty_turn);
            // 使用後ターンを終えるかどうか
            return true;
        }
        // すすむマス
        elseif(preg_match("/[0-9]+マスすすむ/",$square_effect)){
            // 数値抽出
            $number = (int)str_replace("マスすすむ","",$square_effect);
            Ivent::addPlayerPosition($player,$number);
        }
        // もどるマス
        elseif(preg_match("/[0-9]+マスもどる/",$square_effect)){
            // 数値抽出
            $number = (int)str_replace("マス戻る","",$square_effect);
            Ivent::reducePlayerPosition($player,$number);
        }
        // アイテムマス
        elseif($square_effect == "アイテム"){
            Ivent::recieveItem($player);
            return false;
        }
        // ペナルティターンマス
        elseif(preg_match("/[0-9]+ターンやすみ/",$square_effect)){
            $number = (int)str_replace("ターンやすみ","",$square_effect);
            Ivent::addPrenaltyTurn($player,$number);
        }
        // 何もなしマス（例外なマスも）
        else{
            Ivent::nothing();
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
        echo "使うと",$item->getIvent(),"効果がある\n";
        WaitProcessing::sleep(0.4);
    }
    // 単純移動
    public static function addPlayerPosition($plyaer,$number){
        WaitProcessing::sleep(0.5);
        echo $number,"マスすすむ。\n";
        $plyaer->addPosition($number);
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

}

?>