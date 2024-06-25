<?php
session_start();

require_once '../config/database.php';
require_once '../controllers/GameController.php';

$gameController = new GameController($pdo);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['start_game'])) {
        $target_number = rand(1, 100);
        
        $game_id = $gameController->startGame($_SESSION['user_id'], $target_number);
        if ($game_id) {
            $_SESSION['game_id'] = $game_id; // Yeni oyun ID'sini sakla
            echo "Game started! Try to guess the number between 1 and 100.";
        } else {
            echo "Failed to start the game.";
        }
    } elseif (isset($_POST['make_guess'])) {
        $game_id = $_POST['game_id'];
        $guess = $_POST['guess'];
        echo $gameController->makeGuess($game_id, $guess);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Number Guessing Game</title>
</head>
<body>
    <h1>Number Guessing Game</h1>
   
        <form method="post">
            <button type="submit" name="start_game">Start New Game</button>
        </form>
        <br>
        <form method="post">
            <input type="hidden" name="game_id" value="<?php echo $_SESSION['game_id'] ?? ''; ?>">
            <input type="number" name="guess" placeholder="Enter your guess" required>
            <button type="submit" name="make_guess">Make Guess</button>
        </form>
    
</body>
</html>
