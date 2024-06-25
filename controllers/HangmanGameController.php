<?php
require_once '../models/HangmanGame.php';

class HangmanGameController {
    private $gameModel;

    public function __construct($pdo) {
        $this->gameModel = new HangmanGame($pdo);
    }

    public function startGame($user_id, $word_id, $max_attempts) {
        return $this->gameModel->startGame($user_id, $word_id, $max_attempts);
    }

    public function makeGuess($game_id, $letter) {
        return $this->gameModel->makeGuess($game_id, $letter);
    }
}
?>
