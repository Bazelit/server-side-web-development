<?php
$host = '127.0.0.1';
$port = '5432';
$dbname = 'recipes_db';
$user = 'recipes_user';
$password = 'recipes_pass';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Успешное подключение к PostgreSQL!";
    
    // Проверим таблицы
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    echo "<h3>Таблицы в базе:</h3>";
    foreach ($stmt->fetchAll() as $row) {
        echo $row['table_name'] . "<br>";
    }
} catch (PDOException $e) {
    echo "❌ Ошибка: " . $e->getMessage();
}
?>