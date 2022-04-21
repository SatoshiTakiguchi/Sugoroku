<?php

require_once 'Classes/WaitProcessing.php';
require_once 'Classes/Item.php';

class Ivent{

    public static function apply($plyaer_list,$player,$square){
        if($square == "何もなし"){
            Ivent::nothing();
        }
        // すすむマス
        if(preg_match("/[0-9]+マスすすむ/",$square)){
            Ivent::addPlayerPosition($player,$square);
        }
        // もどるマス
        if(preg_match("/[0-9]+マスもどる/",$square)){
            Ivent::reducePlayerPosition($player,$square);
        }

        if($square == "アイテム"){
            Ivent::getItem($player);
        }
    }

    private static function nothing(){
        echo "何もなし\n";
    }

    private static function getItem($player){
        echo "アイテムマスに止まった。\n";
        WaitProcessing::sleep(0.5);
        $item = new Item();
        $player->addItem($item);
        echo $item->getName(),"を獲得した！\n";
        echo "使うと",$item->getIvent(),"効果がある\n";
    }

    // 単純移動マス
    private static function addPlayerPosition($plyaer,$square){
        // 数値抽出
        $number = (int)str_replace("マスすすむ","",$square);

        echo $number,"すすむマスに止まった。\n";
        WaitProcessing::sleep(0.5);
        echo $number,"マスすすむ。\n";
        $plyaer->addPosition($number);
    }
    private static function reducePlayerPosition($player,$square){
        // 数値抽出
        $number = (int)str_replace("マス戻る","",$square);

        echo $number,"もどるマスに止まった。\n";
        WaitProcessing::sleep(0.5);
        echo $number,"マスもどる。\n";
        $player->addPosition(-$number);
    }

}

?>