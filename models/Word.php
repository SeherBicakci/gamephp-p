<?php
class Word {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getRandomWord() {
        $stmt = $this->pdo->prepare("SELECT * FROM words ORDER BY RANDOM() LIMIT 1");
        if ($stmt->execute()) {
            return $stmt->fetch();
        }
        return false;
    }
}
?>
