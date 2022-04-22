<?php

class Board{
    private static $board_range = 30;
    private static $square_src_list = [
        "何もなし",
        "1マスすすむ",
        "2マスすすむ",
        "3マスすすむ",
        "1マスもどる",
        "アイテム",
        "1回やすみ",
        "2回やすみ",
    ];

    public static function createRandomBoad($data_path = 'data/board1.csv'){
        $fp = fopen($data_path,'w');
        // CSV入力
        for($i = 0; $i < Board::$board_range; $i++){
            $key = array_rand(Board::$square_src_list);
            fputcsv($fp,[Board::$square_src_list[$key]]);
        }
        fclose($fp);
    }

    private $square_list = [];

    public function __construct($data_path = 'data/board.csv'){
        $this->readCSV($data_path);
    }

    // データ取得関数
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
    }
}

?>