<?php

require_once 'Classes/Ivent.php';

class Item{

    private $ivent_list;
    private $name;
    private $ivent;

    public function __construct(
        
    ){
        $this->ivent_list = [
            '自動車'   => "1マスすすむ",
            '新幹線'   => "2マスすすむ",
            'ロケット' => "3マスすすむ",
        ];
        $this->name = array_rand($this->ivent_list);
        $this->ivent = $this->ivent_list[$this->name];
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