<?php

class WaitProcessing{

    public static function sleep($time,$isAplly = false){
        if (!$isAplly){
            return;
        }
        $time = $time * 1000000;
        usleep($time);
    }

    public static function enter($isAuto = false){
        if ($isAuto){
            WaitProcessing::sleep(1);
            echo "\n";
            return;
        }
        fgets(STDIN);
    }

    public static function submit($selected_word){
        echo "\n{$selected_word}でよろしいですか\n";
        echo "5:大丈夫\n";
        echo "5以外:やり直す\n";
        $flag = fgets(STDIN);
        echo "\n";
        if($flag == 5){
            return true;
        }
        echo "やり直します\n";
        return false;
    }
}


?>