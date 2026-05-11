<?php
// index.php (роутер)

// Получаем URI
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Убираем путь к скрипту из URI
if ($scriptName != '/') {
    $requestUri = substr($requestUri, strlen($scriptName));
}

// Разбираем URI
$path = parse_url($requestUri, PHP_URL_PATH);
$path = rtrim($path, '/');

// Убедимся, что path начинается со слэша
if ($path && $path[0] !== '/') {
    $path = '/' . $path;
}

// Подключаем контроллеры
require_once 'controllers/ArticleController.php';

// Контроллер
class BlogController {
    
    public function index() {
        include 'views/main.php';
    }
    
    public function aboutMe() {
        include 'views/about-me.php';
    }
    
    public function sayBye($name) {
        $name = urldecode($name);
        echo "Пока, " . htmlspecialchars($name);
    }
}

// Роутинг
if ($path == '' || $path == '/') {
    $controller = new BlogController();
    $controller->index();
} 
elseif ($path == '/about-me') {
    $controller = new BlogController();
    $controller->aboutMe();
}
elseif (preg_match('#^/bye/(.+)$#', $path, $matches)) {
    $controller = new BlogController();
    $name = $matches[1];
    $controller->sayBye($name);
}
elseif (preg_match('~^/article/(\d+)/edit$~', $path, $matches)) {
    $controller = new \MyProject\Controllers\ArticleController();
    $articleId = $matches[1];
    $controller->edit($articleId);
}
elseif (preg_match('~^/article/(\d+)$~', $path, $matches)) {
    $controller = new \MyProject\Controllers\ArticleController();
    $articleId = $matches[1];
    $controller->view($articleId);
}
else {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Страница не найдена";
}