<?php

class Board{
    private $trout_list = [];

    public function __construct($data_path = 'data/board.csv'){
        $this->readCSV($data_path);
    }

    public function getBorad(){
        return $this->trout_list;
    }

    // CSVからボード読み込み
    public function readCSV($data_path){
        $f = fopen($data_path,"r");
        $this->trout_list[] = "スタート";
        while($data = fgetcsv($f)){
            $this->trout_list[] = $data[0];
        }
        $this->trout_list[] = "ゴール";
        print_r($this->trout_list);
    }
}

?>