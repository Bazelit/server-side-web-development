<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Список возможных фраз
$messages = [
    "Hello, World!",
    "Привет, Мир!",
    "Hola, Mundo!",
    "Bonjour le Monde!",
    "Ciao, Mondo!",
    "Hallo, Welt!",
    "Olá, Mundo!",
    "Hej, Världen!"
];

// Файл для хранения текущего индекса
$stateFile = __DIR__ . '/state.json';

// Инициализация состояния (если файла нет)
if (!file_exists($stateFile)) {
    file_put_contents($stateFile, json_encode(['currentIndex' => 0]));
}

// Чтение текущего индекса
function getCurrentIndex() {
    global $stateFile;
    $data = json_decode(file_get_contents($stateFile), true);
    return $data['currentIndex'] ?? 0;
}

// Сохранение индекса
function saveCurrentIndex($index) {
    global $stateFile;
    file_put_contents($stateFile, json_encode(['currentIndex' => $index]));
}

// Получение действия из запроса
$action = $_POST['action'] ?? $_GET['action'] ?? 'get';

switch ($action) {
    case 'next':
        $currentIndex = getCurrentIndex();
        $nextIndex = ($currentIndex + 1) % count($GLOBALS['messages']);
        saveCurrentIndex($nextIndex);
        echo json_encode([
            'success' => true,
            'message' => $GLOBALS['messages'][$nextIndex],
            'index' => $nextIndex
        ]);
        break;
        
    case 'reset':
        saveCurrentIndex(0);
        echo json_encode([
            'success' => true,
            'message' => $GLOBALS['messages'][0],
            'index' => 0
        ]);
        break;
        
    case 'get':
    default:
        $currentIndex = getCurrentIndex();
        echo json_encode([
            'success' => true,
            'message' => $GLOBALS['messages'][$currentIndex],
            'index' => $currentIndex
        ]);
        break;
}
?>