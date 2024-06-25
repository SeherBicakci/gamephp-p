<?php
require_once '../models/CityGuessingGame.php';

class CityGameController {
    private $gameModel;

    public function __construct($pdo) {
        $this->gameModel = new CityGuessingGame($pdo);
    }

    public function startGame($user_id, $city_id) {
        return $this->gameModel->startGame($user_id, $city_id);
    }

    public function makeGuess($game_id, $guess) {
        return $this->gameModel->makeGuess($game_id, $guess);
    }
}
?>
