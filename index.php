<?php
session_start();
require_once 'config/database.php';
require_once 'controllers/UserController.php';



$userController = new UserController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($userController->register($username, $email, $password)) {
            echo "Registration successful!";
        } else {
            echo "Registration failed!";
        }
    } elseif (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $userController->login($username, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            echo "Login successful! ". $user['id'];
        } else {
            echo "Login failed!";
        }
    } 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Game</title>
</head>
<body>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <form method="post">
            <h2>Register</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        
        <form method="post">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    <?php else: ?>
        <h1>Oyunlar</h1>
        <ul>
            <li><a href="views/number_guessing_game.php">Sayı Bulma Oyunu</a></li>
            <li><a href="views/country_guessing_game.php">Ülke Bulma Oyunu</a></li>
            <li><a href="views/city_guessing_game.php">Şehir Bulma Oyunu</a></li>
            <li><a href="views/hangman_game.php">Adam Asmaca Oyunu</a></li>
            <li><a href="views/battleship_game.php">Amiral Battı Oyunu</a></li>
            <li><a href="views/chess_game.php">Satranç Oyunu</a></li>
        </ul>
        
    <?php endif; ?>
</body>
</html>
