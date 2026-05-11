<?php
// page2.php - Страница 2: Результат work функции get_headers

// Функция для получения всех заголовков HTTP запроса
function get_request_headers() {
    $headers = [];
    
    // Получаем все заголовки
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            // Преобразуем ключ в понятный формат
            $header_key = str_replace('_', '-', substr($key, 5));
            $header_key = ucwords(strtolower($header_key), '-');
            $headers[$header_key] = $value;
        }
    }
    
    return $headers;
}

// Получаем информацию о клиенте и заголовках
$headers_data = [
    'timestamp' => date('c'), // ISO 8601 формат
    'clientIp' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
    'protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
    'allHeaders' => get_request_headers()
];

// Форматируем вывод
$headers_json = json_encode($headers_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты - Форма обратной связи</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
               <img
                    width="250"
                    class="logo"
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvp42kooFZKGZYiw4purSlyg0t9IGIeyBH3w&s"
                    alt="logo"
                />
                <h1 class="header-title">Форма обратной связи</h1>
                <h2>Выполнил Гумашян Давид Артакович 251-3210</h2>
                <div class="header-spacer"></div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main">
            <div class="headers-container">
                <h2>Результат работы функции get_headers</h2>
                <textarea id="headersOutput" class="headers-textarea" readonly><?php echo htmlspecialchars($headers_json); ?></textarea>
                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">Вернуться на страницу 1</a>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>Задание для самостоятельной работы</p>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>
