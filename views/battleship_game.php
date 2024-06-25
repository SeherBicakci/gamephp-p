<?php
session_start();

require_once '../config/database.php';
require_once '../controllers/BattleshipGameController.php';

$gameController = new BattleshipGameController($pdo);

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
            $_SESSION['battleship_game_id'] = $game_id;
        } else {
            $message = "Failed to start the game.";
        }
    } elseif (isset($_POST['place_ship'])) {
        $game_id = $_SESSION['battleship_game_id'];
        $ship_positions = explode(',', $_POST['ship_positions']);
        $message = $gameController->placeShip($game_id, $user_id, $ship_positions);
    } elseif (isset($_POST['make_move'])) {
        $game_id = $_SESSION['battleship_game_id'];
        $position = $_POST['position'];
        $message = $gameController->makeMove($game_id, $user_id, $position);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Battleship Game</title>
</head>
<body>
    <h1>Battleship Game</h1>
    <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (!isset($_SESSION['battleship_game_id'])): ?>
        <form method="post">
            <label for="player2_id">Player 2 ID:</label>
            <input type="text" name="player2_id" id="player2_id" required>
            <button type="submit" name="start_game">Start New Game</button>
        </form>
    <?php else: ?>
        <form method="post">
            <label for="ship_positions">Place Ships (comma-separated positions):</label>
            <input type="text" name="ship_positions" id="ship_positions" required>
            <button type="submit" name="place_ship">Place Ships</button>
        </form>
        <form method="post">
            <label for="position">Make Move (position):</label>
            <input type="text" name="position" id="position" required>
            <button type="submit" name="make_move">Make Move</button>
        </form>
    <?php endif; ?>
</body>
</html>
