<?php
// index.php (роутер)

// Базовая директория проекта
define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/controllers/ArticlesController.php';
require_once BASE_PATH . '/controllers/CommentsController.php';

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

// Определяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];

// Заголовок по умолчанию
$pageTitle   = 'Мой блог';
$contentFile = null;

// Роутинг
if ($path == '' || $path == '/') {
    $pageTitle   = 'Главная';
    $contentFile = BASE_PATH . '/views/main.php';

} elseif ($path == '/about-me') {
    $pageTitle   = 'Обо мне';
    $contentFile = BASE_PATH . '/views/about-me.php';

} elseif (preg_match('#^/bye/(.+)$#', $path, $matches)) {
    $name = urldecode($matches[1]);
    echo "Пока, " . htmlspecialchars($name);
    exit;

// POST /articles/123/comments — добавить комментарий
} elseif (preg_match('#^/articles/(\d+)/comments$#', $path, $matches) && $method === 'POST') {
    $articleId = (int)$matches[1];
    $controller = new CommentsController();
    $controller->store($articleId);
    exit;

// GET /articles/123 — страница статьи
} elseif (preg_match('#^/articles/(\d+)$#', $path, $matches)) {
    $articleId = (int)$matches[1];
    $controller = new ArticlesController();
    $controller->show($articleId);
    exit;

// GET|POST /comments/456/edit — редактировать комментарий
} elseif (preg_match('#^/comments/(\d+)/edit$#', $path, $matches)) {
    $commentId = (int)$matches[1];
    $controller = new CommentsController();
    $controller->edit($commentId);
    exit;

} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Страница не найдена";
    exit;
}

// Отрисовка с layout (для маршрутов главной и about-me)
if ($contentFile) {
    include BASE_PATH . '/views/layout.php';
}
