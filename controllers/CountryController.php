<?php
require_once '../models/Country.php';

class CountryController {
    private $countryModel;

    public function __construct($pdo) {
        $this->countryModel = new Country($pdo);
    }

    public function getRandomCountry() {
        $deneme = $this->countryModel->getRandomCountry();
        return $deneme;
    }
}
?>
