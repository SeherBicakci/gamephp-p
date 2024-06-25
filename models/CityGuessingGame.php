<?php
class CityGuessingGame {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function startGame($user_id, $city_id) {
        $stmt = $this->pdo->prepare("INSERT INTO city_guessing_games (user_id, city_id, attempts, score) VALUES (?, ?, 0, 0)");
        if ($stmt->execute([$user_id, $city_id])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function makeGuess($game_id, $guess) {
        $stmt = $this->pdo->prepare("SELECT cities.city_name, city_guessing_games.attempts FROM city_guessing_games JOIN cities ON city_guessing_games.city_id = cities.id WHERE city_guessing_games.id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();
        $game['attempts']++;

        if (strcasecmp($guess, $game['city_name']) == 0) {
            $stmt = $this->pdo->prepare("UPDATE city_guessing_games SET attempts = ?, score = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], 100 - $game['attempts'], $game_id]);
            return "Correct! Your score is " . (100 - $game['attempts']);
        } else {
            $stmt = $this->pdo->prepare("UPDATE city_guessing_games SET attempts = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], $game_id]);
            return "Try again!";
        }
    }
}
?>
