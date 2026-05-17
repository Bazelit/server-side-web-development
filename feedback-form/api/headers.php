<?php
// api/headers.php - API эндпоинт для получения заголовков HTTP

header('Content-Type: application/json; charset=utf-8');

// Функция для получения всех заголовков HTTP запроса
function getHeaders() {
    $headers = [];
    
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $header_key = str_replace('_', '-', substr($key, 5));
            $header_key = ucwords(strtolower($header_key), '-');
            $headers[$header_key] = $value;
        }
    }
    
    return $headers;
}

// Создаем ответ
$response = [
    'timestamp' => date('c'),
    'clientIp' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
    'protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
    'allHeaders' => getHeaders()
];

// Отправляем JSON ответ
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
