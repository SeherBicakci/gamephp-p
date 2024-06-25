<?php
class CountryGuessingGame {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function startGame($user_id, $country_id) {
        $stmt = $this->pdo->prepare("INSERT INTO country_guessing_games (user_id, country_id, attempts, score) VALUES (?, ?, 0, 0)");
        if ($stmt->execute([$user_id, $country_id])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function makeGuess($game_id, $guess) {
        $stmt = $this->pdo->prepare("SELECT countries.country_name, country_guessing_games.attempts FROM country_guessing_games JOIN countries ON country_guessing_games.country_id = countries.id WHERE country_guessing_games.id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();
        $game['attempts']++;

        if (strcasecmp($guess, $game['country_name']) == 0) {
            $stmt = $this->pdo->prepare("UPDATE country_guessing_games SET attempts = ?, score = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], 100 - $game['attempts'], $game_id]);
            return "Correct! Your score is " . (100 - $game['attempts']);
        } else {
            $stmt = $this->pdo->prepare("UPDATE country_guessing_games SET attempts = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], $game_id]);
            return "Try again!";
        }
    }
}
?>
