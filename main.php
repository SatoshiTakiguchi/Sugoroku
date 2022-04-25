<?php
require 'Classes/Board.php';
require 'Classes/Dice.php';
require 'Classes/Game.php';
require 'Classes/Player.php';

Board::createRandomBoard();

$game = new Game();
$game->addPlayer(new Player("Taro",$isAuto=false));
$game->addPlayer(new Player("Jiro",$isAuto=true));
// $game->addPlayer(new Player("Saburo",$isAuto=true));
$game->setBoard(new Board('data/board1.csv'));

$game->start();

?>