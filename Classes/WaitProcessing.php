<?php

class WaitProcessing{

    public static function sleep($time,$isAplly = false){
        if (!$isAplly){
            return;
        }
        $time = $time * 1000000;
        usleep($time);
    }

    public static function enter($isAplly = false){
        if (!$isAplly){
            return;
        }
        echo "EnterKeyを押してください";
        fgets(STDIN);
    }
}


?>