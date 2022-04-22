<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Item.php';

class Ivent{

    public static function apply($plyaer_list,$player,$square_effect){
        // 何もなしマス
        if($square_effect == "何もなし"){
            Ivent::nothing();
        }
        // すすむマス
        if(preg_match("/[0-9]+マスすすむ/",$square_effect)){
            Ivent::addPlayerPosition($player,$square_effect);
        }
        // もどるマス
        if(preg_match("/[0-9]+マスもどる/",$square_effect)){
            Ivent::reducePlayerPosition($player,$square_effect);
        }
        // アイテムマス
        if($square_effect == "アイテム"){
            Ivent::getItem($player);
        }
    }

    // 何もなしマス
    private static function nothing(){
        echo "何もなし\n";
    }
    // アイテムマス
    private static function getItem($player){
        echo "アイテムマスに止まった。\n";
        WaitProcessing::sleep(0.5);
        $item = new Item();
        $player->addItem($item);
        echo $item->getName(),"を獲得した！\n";
        echo "使うと",$item->getIvent(),"効果がある\n";
    }
    // 単純移動マス
    private static function addPlayerPosition($plyaer,$square_effect){
        // 数値抽出
        $number = (int)str_replace("マスすすむ","",$square_effect);

        WaitProcessing::sleep(0.5);
        echo $number,"マスすすむ。\n";
        $plyaer->addPosition($number);
    }
    private static function reducePlayerPosition($player,$square_effect){
        // 数値抽出
        $number = (int)str_replace("マス戻る","",$square_effect);

        WaitProcessing::sleep(0.5);
        echo $number,"マスもどる。\n";
        $player->addPosition(-$number);
    }

}

?>