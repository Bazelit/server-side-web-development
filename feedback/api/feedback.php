<?php
// api/feedback.php - API эндпоинт для обработки формы обратной связи

header('Content-Type: application/json; charset=utf-8');

// Обработка POST запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные формы
    $feedback = [
        'timestamp' => date('c'),
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'feedbackType' => $_POST['feedbackType'] ?? '',
        'message' => $_POST['message'] ?? '',
        'responseMethod' => isset($_POST['response_method']) ? $_POST['response_method'] : [],
        'clientIp' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
        'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ];
    
    // Валидация данных
    if (empty($feedback['name']) || empty($feedback['email']) || empty($feedback['feedbackType']) || empty($feedback['message'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Все поля обязательны для заполнения'
        ]);
        exit;
    }
    
    // Валидация e-mail
    if (!filter_var($feedback['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Некорректный e-mail адрес'
        ]);
        exit;
    }
    
    // Здесь можно сохранить данные в БД или отправить на почту
    // Например, логирование в файл
    $logEntry = json_encode($feedback, JSON_UNESCAPED_UNICODE) . "\n";
    @file_put_contents(__DIR__ . '/../feedback_log.json', $logEntry, FILE_APPEND);
    
    // Успешный ответ
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Спасибо! Ваша обратная связь принята.',
        'data' => $feedback
    ]);
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Метод не допускается. Используйте POST.'
    ]);
}
?>
