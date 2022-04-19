<?php
require 'Classes/Board.php';
require 'Classes/Dice.php';
require 'Classes/Game.php';
require 'Classes/Player.php';

$dice = new Dice();

$i = $dice->DiceRoll();

$board = new Board();

$list = $board->getBorad();
// print_r($list);

?>