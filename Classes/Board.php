<?php

class Board{
    private $trout_list = [];

    public function __construct(
        $board_range = 20
    )
    {
        for($i = 0; $i < $board_range; $i++){
            $this->trout_list[] = "nothing";
        }
        $this->createCSV();
    }

    public function getBorad(){
        return $this->trout_list;
    }

    public function createCSV(){
        // ファイルを開く
        $fp = fopen('BOARD.csv', 'w');
        // 1行ずつ配列の内容をファイルに書き込む
        foreach ($this->trout_list as $num => $val) {
            fputcsv($fp, [$num,$val]);
        }
        // ファイルを閉じる
        fclose($fp);
    }
}

?>