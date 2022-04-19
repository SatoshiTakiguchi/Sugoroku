<?php
require 'Classes/Board.php';
require 'Classes/Dice.php';
require 'Classes/Game.php';
require 'Classes/Player.php';

$game = new Game();
$game->addPlayer(new Player("Taro"));
$game->addPlayer(new Player("Jiro"));
$game->setBoard(new Board('data/board.csv'));
// $geme->setDice(new Dice());
$game->start();

?>