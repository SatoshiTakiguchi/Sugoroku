<?php

class Ivent{

    public static function apply($square){
        if($square == "nothing"){
            Ivent::nothing();
        }
    }

    private static function nothing(){
        echo "何もない\n";
    }

}

?>