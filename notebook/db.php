<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'postgres');
define('DB_PASS', '1234');
define('DB_NAME', 'phonebook');
define('DB_PORT', '5432');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die('<p style="color:red;text-align:center">Ошибка подключения: ' . htmlspecialchars($e->getMessage()) . '</p>');
        }
    }
    return $pdo;
}