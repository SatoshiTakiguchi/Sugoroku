<?php

class Board{
    private $trout_list = [];

    public function __construct(
        $data_path   = 'data/board.csv',
        $board_range = 20
    )
    {
        $this->trout_list[] = "スタート";
        for($i = 0; $i < $board_range; $i++){
            $this->trout_list[] = "nothing";
        }
        $this->trout_list[] = "ゴール";
        $this->createCSV($data_path);
    }

    public function getBorad(){
        return $this->trout_list;
    }

    public function createCSV($data_path){
        // ファイルを開く
        $fp = fopen($data_path, 'w');
        // 1行ずつ配列の内容をファイルに書き込む
        foreach ($this->trout_list as $num => $val) {
            fputcsv($fp, [$num,$val]);
        }
        // ファイルを閉じる
        fclose($fp);
    }
}

?>