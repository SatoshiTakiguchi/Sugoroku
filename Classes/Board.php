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
    }

    public function getBorad(){
        return $this->trout_list;
    }
}

?>