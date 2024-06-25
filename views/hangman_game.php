<?php
session_start();

require_once '../config/database.php';
require_once '../controllers/WordController.php';
require_once '../controllers/HangmanGameController.php';

$wordController = new WordController($pdo);
$gameController = new HangmanGameController($pdo);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['start_game'])) {
        $word = $wordController->getRandomWord();
        if ($word) {
            $max_attempts = 6; // Adam Asmaca için maksimum deneme sayısı
            $game_id = $gameController->startGame($user_id, $word['id'], $max_attempts);
            if ($game_id) {
                $_SESSION['hangman_game_id'] = $game_id;
            } else {
                $message = "Failed to start the game.";
            }
        } else {
            $message = "No words found.";
        }
    } elseif (isset($_POST['make_guess'])) {
        $game_id = $_SESSION['hangman_game_id'];
        $letter = $_POST['letter'];
        $message = $gameController->makeGuess($game_id, $letter);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hangman Game</title>
</head>
<body>
    <h1>Hangman Game</h1>
    <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (!isset($_SESSION['hangman_game_id'])): ?>
        <form method="post">
            <button type="submit" name="start_game">Start New Game</button>
        </form>
    <?php else: ?>
        <form method="post">
            <input type="text" name="letter" placeholder="Enter a letter" required maxlength="1">
            <button type="submit" name="make_guess">Make Guess</button>
        </form>
    <?php endif; ?>
</body>
</html>
