<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Item.php';

class Ivent{

    public static function apply($game,$player,$square_effect){
        // 
        if(preg_match("/指定した人/",$square_effect)){
            echo "mach\n";
            print_r($game->selectOtherPlayer($player));
            sleep(10);
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
        }
        // ペナルティターンマス
        elseif(preg_match("/[0-9]+回やすみ/",$square_effect)){
            $number = (int)str_replace("回やすみ","",$square_effect);
            Ivent::addPrenaltyTurn($player,$number);
        }
        // 何もなしマス（例外なマスも）
        else{
            Ivent::nothing();
        }
        
    }

    // 何もなしマス
    public static function nothing(){
        echo "何もなし\n";
    }
    // アイテムマス
    public static function recieveItem($player){
        WaitProcessing::sleep(0.5);
        $item = new Item();
        $player->addItem($item);
        echo $item->getName(),"を獲得した！\n";
        WaitProcessing::sleep(0.4);
        echo "使うと",$item->getIvent(),"効果がある\n";
        WaitProcessing::sleep(0.4);
    }
    // 単純移動マス
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
    // ペナルティターンマス
    public static function addPrenaltyTurn($player,$penalty_turn){
        WaitProcessing::sleep(0.5);
        echo $player->getName(),"さんは",$penalty_turn,"ターン動けなくなった。\n";
        $player->addPenaltyTurn($penalty_turn);
    }

}

?>