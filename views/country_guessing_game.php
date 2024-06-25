<?php
session_start();

require_once '../config/database.php';
require_once '../controllers/CountryController.php';
require_once '../controllers/CountryGameController.php';

$countryController = new CountryController($pdo);
$gameController = new CountryGameController($pdo);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['start_game'])) {
        $country = $countryController->getRandomCountry();
        if ($country) {
            $game_id = $gameController->startGame($user_id, $country['id']);
            if ($game_id) {
                $_SESSION['country_game_id'] = $game_id;
                $_SESSION['country_hint'] = $country['hint'];
            } else {
                $message = "Failed to start the game.";
            }
        } else {
            $message = "No countries found.";
        }
    } elseif (isset($_POST['make_guess'])) {
        $game_id = $_SESSION['country_game_id'];
        $guess = $_POST['guess'];
        $message = $gameController->makeGuess($game_id, $guess);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Country Guessing Game</title>
</head>
<body>
    <h1>Country Guessing Game</h1>
    <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    
        <form method="post">
            <button type="submit" name="start_game">Start New Game</button>
        </form>
    
        <p>Hint: <?php echo htmlspecialchars($_SESSION['country_hint']); ?></p>
        <form method="post">
            <input type="text" name="guess" placeholder="Enter your guess" required>
            <button type="submit" name="make_guess">Make Guess</button>
        </form>
   
</body>
</html>
