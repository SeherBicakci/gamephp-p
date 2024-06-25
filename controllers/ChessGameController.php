<?php
require_once '../models/ChessGame.php';

class ChessGameController {
    private $gameModel;

    public function __construct($pdo) {
        $this->gameModel = new ChessGame($pdo);
    }

    public function startGame($player1_id, $player2_id) {
        return $this->gameModel->startGame($player1_id, $player2_id);
    }

    public function makeMove($game_id, $player_id, $from, $to) {
        return $this->gameModel->makeMove($game_id, $player_id, $from, $to);
    }

    public function getGameState($game_id) {
        return $this->gameModel->getGameState($game_id);
    }
}
?>
