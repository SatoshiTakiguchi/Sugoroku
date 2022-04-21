<?php

require_once 'Classes/WaitProcessing.php';

class Ivent{

    public static function apply($player,$square){
        if($square == "何もなし"){
            Ivent::nothing();
        }
        if($square == "1"){
            Ivent::addPlayerPosition($player,$square);
        }
        if($square == "-1"){
            Ivent::addPlayerPosition($player,$square);
        }
    }

    private static function nothing(){
        echo "何もなし\n";
    }

    // 単純移動マス
    private static function addPlayerPosition($plyaer,$number){
        $number = (int)$number;
        if($number<0){
            echo abs($number),"もどるマスに止まった。\n";
            WaitProcessing::sleep(0.5);
            echo abs($number),"マスもどる。\n";
        }else{
            echo abs($number),"すすむマスに止まった。\n";
            WaitProcessing::sleep(0.5);
            echo abs($number),"マスすすむ。\n";
        }
        echo $number;
        $plyaer->addPosition($number);
    }

}

?>