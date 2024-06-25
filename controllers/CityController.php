<?php
require_once '../models/City.php';

class CityController {
    private $cityModel;

    public function __construct($pdo) {
        $this->cityModel = new City($pdo);
    }

    public function getRandomCity() {
        return $this->cityModel->getRandomCity();
    }
}
?>
