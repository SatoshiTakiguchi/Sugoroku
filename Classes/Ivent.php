<?php

require_once 'Classes/WaitProcessing.php';

class Ivent{

    public static function apply($player,$square){
        if($square == "何もなし"){
            Ivent::nothing();
        }
        if(preg_match("/[0-9]+マスすすむ/",$square)){
            $number = (int)str_replace("マスすすむ","",$square);
            Ivent::addPlayerPosition($player,$number);
        }
        if(preg_match("/[0-9]+マスもどる/",$square)){
            $number = (int)str_replace("マス戻る","",$square);
            Ivent::reducePlayerPosition($player,$number);
        }
    }

    private static function nothing(){
        echo "何もなし\n";
    }

    // 単純移動マス
    private static function addPlayerPosition($plyaer,$number){
        echo abs($number),"すすむマスに止まった。\n";
        WaitProcessing::sleep(0.5);
        echo abs($number),"マスすすむ。\n";
        $plyaer->addPosition($number);
    }
    private static function reducePlayerPosition($player,$number){
        echo $number,"もどるマスに止まった。\n";
        WaitProcessing::sleep(0.5);
        echo $number,"マスもどる。\n";
        $player->addPosition(-$number);
    }

}

?>