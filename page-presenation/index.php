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

// Заголовок по умолчанию
$pageTitle = 'Мой блог';
$contentFile = null;

// Роутинг
if ($path == '' || $path == '/') {
    $contentFile = 'views/main.php';
} 
elseif ($path == '/about-me') {
    $contentFile = 'views/about-me.php';
}
elseif (preg_match('#^/hello/(.+)$#', $path, $matches)) {
    $name = urldecode($matches[1]);
    $pageTitle = 'Страница приветствия';
    $content = '<h1>Привет, ' . htmlspecialchars($name) . '!</h1>' .
        '<p>Добро пожаловать на страницу приветствия!</p>';
    include 'views/layout.php';
    exit;
}
elseif (preg_match('#^/bye/(.+)$#', $path, $matches)) {
    $name = urldecode($matches[1]);
    echo "Пока, " . htmlspecialchars($name);
    exit;
}
else {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Страница не найдена";
    exit;
}

// Отрисовка с layout
if ($contentFile) {
    include 'views/layout.php';
}