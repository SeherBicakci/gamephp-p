<?php
require_once '../models/BattleshipGame.php';

class BattleshipGameController {
    private $gameModel;

    public function __construct($pdo) {
        $this->gameModel = new BattleshipGame($pdo);
    }

    public function startGame($player1_id, $player2_id) {
        return $this->gameModel->startGame($player1_id, $player2_id);
    }

    public function placeShip($game_id, $player_id, $ship_positions) {
        return $this->gameModel->placeShip($game_id, $player_id, $ship_positions);
    }

    public function makeMove($game_id, $player_id, $position) {
        return $this->gameModel->makeMove($game_id, $player_id, $position);
    }
}
?>
