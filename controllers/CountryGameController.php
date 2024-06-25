<?php
require_once '../models/CountryGuessingGame.php';

class CountryGameController {
    private $gameModel;

    public function __construct($pdo) {
        $this->gameModel = new CountryGuessingGame($pdo);
    }

    public function startGame($user_id, $country_id) {
        return $this->gameModel->startGame($user_id, $country_id);
    }

    public function makeGuess($game_id, $guess) {
        return $this->gameModel->makeGuess($game_id, $guess);
    }
}
?>
