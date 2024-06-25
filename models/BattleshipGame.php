<?php
class BattleshipGame {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function startGame($player1_id, $player2_id) {
        $empty_board = str_repeat('0', 100); // 10x10 board with all zeros
        $stmt = $this->pdo->prepare("INSERT INTO battleship_games (player1_id, player2_id, player1_board, player2_board, player1_turn, status) VALUES (?, ?, ?, ?, true, 'ongoing')");
        if ($stmt->execute([$player1_id, $player2_id, $empty_board, $empty_board])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function placeShip($game_id, $player_id, $ship_positions) {
        $stmt = $this->pdo->prepare("SELECT * FROM battleship_games WHERE id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();

        if (!$game) {
            return "Game not found.";
        }

        if ($game['player1_id'] == $player_id) {
            $board = str_split($game['player1_board']);
            foreach ($ship_positions as $pos) {
                $board[$pos] = '1';
            }
            $new_board = implode('', $board);
            $stmt = $this->pdo->prepare("UPDATE battleship_games SET player1_board = ? WHERE id = ?");
            $stmt->execute([$new_board, $game_id]);
        } else if ($game['player2_id'] == $player_id) {
            $board = str_split($game['player2_board']);
            foreach ($ship_positions as $pos) {
                $board[$pos] = '1';
            }
            $new_board = implode('', $board);
            $stmt = $this->pdo->prepare("UPDATE battleship_games SET player2_board = ? WHERE id = ?");
            $stmt->execute([$new_board, $game_id]);
        } else {
            return "Invalid player.";
        }

        return "Ships placed.";
    }

    public function makeMove($game_id, $player_id, $position) {
        $stmt = $this->pdo->prepare("SELECT * FROM battleship_games WHERE id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();

        if (!$game) {
            return "Game not found.";
        }

        if ($game['status'] != 'ongoing') {
            return "Game is already finished.";
        }

        if (($game['player1_id'] == $player_id && !$game['player1_turn']) || ($game['player2_id'] == $player_id && $game['player1_turn'])) {
            return "Not your turn.";
        }

        if ($game['player1_id'] == $player_id) {
            $opponent_board = str_split($game['player2_board']);
            $player_turn = false;
        } else if ($game['player2_id'] == $player_id) {
            $opponent_board = str_split($game['player1_board']);
            $player_turn = true;
        } else {
            return "Invalid player.";
        }

        if ($opponent_board[$position] == '1') {
            $opponent_board[$position] = '2'; // Hit
        } else {
            $opponent_board[$position] = '3'; // Miss
        }

        $new_opponent_board = implode('', $opponent_board);
        if ($game['player1_id'] == $player_id) {
            $stmt = $this->pdo->prepare("UPDATE battleship_games SET player2_board = ?, player1_turn = ? WHERE id = ?");
            $stmt->execute([$new_opponent_board, $player_turn, $game_id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE battleship_games SET player1_board = ?, player1_turn = ? WHERE id = ?");
            $stmt->execute([$new_opponent_board, $player_turn, $game_id]);
        }

        if (strpos($new_opponent_board, '1') === false) {
            $stmt = $this->pdo->prepare("UPDATE battleship_games SET status = ? WHERE id = ?");
            $stmt->execute(['finished', $game_id]);
            return "Hit! You win!";
        }

        return "Move made.";
    }
}
?>
