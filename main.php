<?php
require 'Classes/Board.php';
require 'Classes/Dice.php';
require 'Classes/Game.php';
require 'Classes/Player.php';

$game = new Game();
$game->addPlayer(new Player("Taro"));
$game->addPlayer(new Player("Jiro",$isAuto=true));
$game->setBoard(new Board('data/board.csv'));

$game->start();

?>