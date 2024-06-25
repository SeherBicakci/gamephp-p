<?php
class City {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getRandomCity() {
        $stmt = $this->pdo->prepare("SELECT * FROM cities ORDER BY RANDOM() LIMIT 1");
        if ($stmt->execute()) {
            return $stmt->fetch();
        }
        return false;
    }
}
?>
