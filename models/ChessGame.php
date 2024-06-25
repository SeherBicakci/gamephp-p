<?php
class ChessGame {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function startGame($player1_id, $player2_id) {
        $initial_board = 'rnbqkbnrpppppppp................................PPPPPPPPRNBQKBNR'; // Standard chess starting position
        $stmt = $this->pdo->prepare("INSERT INTO chess_games (player1_id, player2_id, board_state, current_turn, status) VALUES (?, ?, ?, ?, 'ongoing')");
        if ($stmt->execute([$player1_id, $player2_id, $initial_board, $player1_id])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function makeMove($game_id, $player_id, $from, $to) {
        $stmt = $this->pdo->prepare("SELECT * FROM chess_games WHERE id = ?");
        $stmt->execute([$game_id]);
        $game = $stmt->fetch();

        if (!$game || $game['status'] != 'ongoing') {
            return "Invalid game or game is already finished.";
        }

        if ($game['current_turn'] != $player_id) {
            return "Not your turn.";
        }

        // Update board state with move (for simplicity, not validating legal moves here)
        $board = str_split($game['board_state']);
        $board[$to] = $board[$from];
        $board[$from] = '.';
        $new_board_state = implode('', $board);

        // Toggle turn
        $next_turn = $game['player1_id'] == $player_id ? $game['player2_id'] : $game['player1_id'];

        $stmt = $this->pdo->prepare("UPDATE chess_games SET board_state = ?, current_turn = ? WHERE id = ?");
        $stmt->execute([$new_board_state, $next_turn, $game_id]);

        return "Move made.";
    }

    public function getGameState($game_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM chess_games WHERE id = ?");
        $stmt->execute([$game_id]);
        return $stmt->fetch();
    }
}
?>
