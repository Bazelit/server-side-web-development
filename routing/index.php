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
else {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Страница не найдена";
}