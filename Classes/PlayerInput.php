<?php

class PlayerInput{

    public static function inputCheck($list,$number){
        $number = trim($number);
        if(0 <= $number && $number < count($list) && $number != ""){
            return true;
        }
        WaitProcessing::sleep(0.2);
        echo "指定された数字を入力してください。\n";
        return false;
    }

}


?>