<?php
// controllers/BlogController.php

class BlogController {
    
    public function index() {
        // Ваш существующий метод
        include 'main.php';
    }
    
    public function aboutMe() {
        // Ваш существующий метод
        include 'about.php';
    }
    
    // Новый метод
    public function sayBye($name) {
        echo "Пока, " . htmlspecialchars($name);
    }
}
?>