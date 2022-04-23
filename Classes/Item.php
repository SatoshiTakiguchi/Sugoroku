<?php

require_once 'Classes/Ivent.php';

class Item{

    private static $ivent_list  = [
        '自動車' => "3マスすすむ",
        '新幹線' => "4マスすすむ",
        '飛行機' => "5マスすすむ",
        '指定'   => "指定した人を2ターン休ませる",
        // '勝利点' => "1ポイント獲得"
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