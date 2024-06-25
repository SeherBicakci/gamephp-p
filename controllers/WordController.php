<?php
require_once '../models/Word.php';

class WordController {
    private $wordModel;

    public function __construct($pdo) {
        $this->wordModel = new Word($pdo);
    }

    public function getRandomWord() {
        return $this->wordModel->getRandomWord();
    }
}
?>
