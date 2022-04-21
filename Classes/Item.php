<?php

require_once 'Classes/Ivent.php';

class Item{

    private static $ivent_list  = [
        '自動車'   => "1マスすすむ",
        '新幹線'   => "2マスすすむ",
        'ロケット' => "3マスすすむ",
    ];
    private $name;
    private $ivent;

    public function __construct(
        
    ){
        $this->name = array_rand(Item::$ivent_list);
        $this->ivent = Item::$ivent_list[$this->name];
    }

    // データ取得関数
    public function getName(){
        return $this->name;
    }
    public function getIvent(){
        return $this->ivent;
    }

}


?>