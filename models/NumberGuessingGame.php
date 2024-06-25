<?php
class NumberGuessingGame {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function startGame($user_id, $target_number) {
        $stmt = $this->pdo->prepare("INSERT INTO number_guessing_games (user_id, target_number, attempts, score) VALUES (?, ?, 0, 0)");
        if ($stmt->execute([$user_id, $target_number])) {
            return $this->pdo->lastInsertId(); // Başarılı olursa yeni oyun ID'sini döndür
        }
        return false; // Başarısız olursa false döndür
    }

    public function makeGuess($game_id, $guess) {
        $stmt = $this->pdo->prepare("SELECT target_number, attempts FROM number_guessing_games WHERE id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();
        $game['attempts']++;
        
        if ($guess == $game['target_number']) {
            $stmt = $this->pdo->prepare("UPDATE number_guessing_games SET attempts = ?, score = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], 100 - $game['attempts'], $game_id]);
            return "Correct! Your score is " . (100 - $game['attempts']);
        } elseif ($guess < $game['target_number']) {
            $stmt = $this->pdo->prepare("UPDATE number_guessing_games SET attempts = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], $game_id]);
            return "Higher!";
        } else {
            $stmt = $this->pdo->prepare("UPDATE number_guessing_games SET attempts = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], $game_id]);
            return "Lower!";
        }
    }
}
?>
