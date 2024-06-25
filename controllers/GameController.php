<?php
require_once '../models/NumberGuessingGame.php';

class GameController {
    private $gameModel;

    public function __construct($pdo) {
        $this->gameModel = new NumberGuessingGame($pdo);
    }

    public function startGame($user_id, $target_number) {
        return $this->gameModel->startGame($user_id, $target_number);
    }

    public function makeGuess($game_id, $guess) {
        return $this->gameModel->makeGuess($game_id, $guess);
    }
}
?>
