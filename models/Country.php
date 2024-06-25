<?php
class Country {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getRandomCountry() {
        $stmt = $this->pdo->prepare("SELECT * FROM countries ORDER BY RANDOM() LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>
