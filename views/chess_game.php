<?php
session_start();

require_once '../config/database.php';
require_once '../controllers/ChessGameController.php';

$gameController = new ChessGameController($pdo);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['start_game'])) {
        $player2_id = $_POST['player2_id'];
        $game_id = $gameController->startGame($user_id, $player2_id);
        if ($game_id) {
            $_SESSION['chess_game_id'] = $game_id;
        } else {
            $message = "Failed to start the game.";
        }
    } elseif (isset($_POST['make_move'])) {
        $game_id = $_SESSION['chess_game_id'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $message = $gameController->makeMove($game_id, $user_id, $from, $to);
    }
}

$game_state = null;
if (isset($_SESSION['chess_game_id'])) {
    $game_id = $_SESSION['chess_game_id'];
    $game_state = $gameController->getGameState($game_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chess Game</title>
</head>
<body>
    <h1>Chess Game</h1>
    <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (!isset($_SESSION['chess_game_id'])): ?>
        <form method="post">
            <label for="player2_id">Player 2 ID:</label>
            <input type="text" name="player2_id" id="player2_id" required>
            <button type="submit" name="start_game">Start New Game</button>
        </form>
    <?php else: ?>
        <div>
            <h2>Current Board State</h2>
            <?php if ($game_state): ?>
                <pre><?php echo htmlspecialchars($game_state['board_state']); ?></pre>
                <p>Current Turn: Player <?php echo $game_state['current_turn']; ?></p>
            <?php endif; ?>
        </div>
        <form method="post">
            <label for="from">Move From (index):</label>
            <input type="text" name="from" id="from" required>
            <label for="to">Move To (index):</label>
            <input type="text" name="to" id="to" required>
            <button type="submit" name="make_move">Make Move</button>
        </form>
    <?php endif; ?>
</body>
</html>
