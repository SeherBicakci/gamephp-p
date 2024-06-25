<?php
class HangmanGame {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function startGame($user_id, $word_id, $max_attempts) {
        $stmt = $this->pdo->prepare("INSERT INTO hangman_games (user_id, word_id, guessed_letters, attempts, max_attempts, status) VALUES (?, ?, '', 0, ?, 'ongoing')");
        if ($stmt->execute([$user_id, $word_id, $max_attempts])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function makeGuess($game_id, $letter) {
        $stmt = $this->pdo->prepare("SELECT * FROM hangman_games JOIN words ON hangman_games.word_id = words.id WHERE hangman_games.id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();
        if (!$game || $game['status'] != 'ongoing') {
            return "Invalid game or game is already finished.";
        }

        if (strpos($game['guessed_letters'], $letter) !== false) {
            return "Letter already guessed.";
        }

        $game['guessed_letters'] .= $letter;
        $game['attempts']++;

        $word = $game['word'];
        $guessed = str_split($game['guessed_letters']);
        $masked_word = '';
        $correct_guess = false;
        foreach (str_split($word) as $char) {
            if (in_array($char, $guessed)) {
                $masked_word .= $char;
                if ($char == $letter) {
                    $correct_guess = true;
                }
            } else {
                $masked_word .= '_';
            }
        }

        if (!$correct_guess) {
            $stmt = $this->pdo->prepare("UPDATE hangman_games SET attempts = ?, guessed_letters = ? WHERE id = ?");
            $stmt->execute([$game['attempts'], $game['guessed_letters'], $game_id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE hangman_games SET guessed_letters = ? WHERE id = ?");
            $stmt->execute([$game['guessed_letters'], $game_id]);
        }

        if ($masked_word == $word) {
            $stmt = $this->pdo->prepare("UPDATE hangman_games SET status = 'won' WHERE id = ?");
            $stmt->execute([$game_id]);
            return "Correct! You've guessed the word: $word.";
        }

        if ($game['attempts'] >= $game['max_attempts']) {
            $stmt = $this->pdo->prepare("UPDATE hangman_games SET status = 'lost' WHERE id = ?");
            $stmt->execute([$game_id]);
            return "Game over! The word was: $word.";
        }

        return "Current word: $masked_word";
    }
}
?>
