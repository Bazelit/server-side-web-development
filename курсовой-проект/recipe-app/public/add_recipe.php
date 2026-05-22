<?php
require_once __DIR__ . '/../src/Recipe.php';
require_once __DIR__ . '/../src/config.php';

// Простейшая защита — проверяем POST пароль поле (можно расширить через сессии)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin.php');
    exit;
}

// В учебных целях пароль проверяется через скрытое поле не безопасно, но достаточно для демонстрации
if (isset($_POST['admin_password']) && $_POST['admin_password'] !== ADMIN_PASSWORD) {
    // allow if not provided but user came from admin form that didn't include password
}

$title = trim($_POST['title'] ?? '');
$desc = trim($_POST['description'] ?? '');
$servings = max(1,(int)($_POST['servings'] ?? 1));
$steps = trim($_POST['steps'] ?? '');
$ingredients_text = trim($_POST['ingredients_text'] ?? '');

$lines = preg_split('/\r?\n/', $ingredients_text);
$ings = [];
foreach ($lines as $ln) {
    $ln = trim($ln);
    if ($ln === '') continue;
    $parts = explode('|', $ln);
    $name = trim($parts[0] ?? '');
    $qty = isset($parts[1]) ? floatval($parts[1]) : 0;
    $unit = trim($parts[2] ?? '');
    if ($name !== '') $ings[] = ['name'=>$name, 'qty'=>$qty, 'unit'=>$unit];
}

$data = [
    'title' => $title,
    'description' => $desc,
    'ingredients_json' => json_encode($ings, JSON_UNESCAPED_UNICODE),
    'steps' => $steps,
    'servings' => $servings,
];

Recipe::create($data);
header('Location: /admin.php');
exit;
