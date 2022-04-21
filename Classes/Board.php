<?php

class Board{
    private $square_list = [];

    public function __construct($data_path = 'data/board.csv'){
        $this->readCSV($data_path);
    }

    public function getBorad(){
        return $this->square_list;
    }

    // CSVからボード読み込み
    public function readCSV($data_path){
        $f = fopen($data_path,"r");
        $this->square_list[] = "スタート";
        while($data = fgetcsv($f)){
            $this->square_list[] = $data[0];
        }
        $this->square_list[] = "ゴール";
        print_r($this->square_list);
    }
}

?>