<?php
require_once 'models/User.php';

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function register($username, $email, $password) {
        return $this->userModel->register($username, $email, $password);
    }

    public function login($username, $password) {
        return $this->userModel->login($username, $password);
    }
}
?>
