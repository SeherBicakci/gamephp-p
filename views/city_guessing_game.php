<?php
session_start();

require_once '../config/database.php';
require_once '../controllers/CityController.php';
require_once '../controllers/CityGameController.php';

$cityController = new CityController($pdo);
$gameController = new CityGameController($pdo);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['start_game'])) {
        $city = $cityController->getRandomCity();
        if ($city) {
            $game_id = $gameController->startGame($user_id, $city['id']);
            if ($game_id) {
                $_SESSION['city_game_id'] = $game_id;
                $_SESSION['city_hint'] = $city['hint'];
            } else {
                $message = "Failed to start the game.";
            }
        } else {
            $message = "No cities found.";
        }
    } elseif (isset($_POST['make_guess'])) {
        $game_id = $_SESSION['city_game_id'];
        $guess = $_POST['guess'];
        $message = $gameController->makeGuess($game_id, $guess);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>City Guessing Game</title>
</head>
<body>
    <h1>City Guessing Game</h1>
    <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (!isset($_SESSION['city_game_id'])): ?>
        <form method="post">
            <button type="submit" name="start_game">Start New Game</button>
        </form>
    <?php else: ?>
        <p>Hint: <?php echo htmlspecialchars($_SESSION['city_hint']); ?></p>
        <form method="post">
            <input type="text" name="guess" placeholder="Enter your guess" required>
            <button type="submit" name="make_guess">Make Guess</button>
        </form>
    <?php endif; ?>
</body>
</html>
