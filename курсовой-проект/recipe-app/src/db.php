<?php
require_once __DIR__ . '/config.php';

function get_db()
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', DB_HOST, DB_PORT, DB_NAME);
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        // Только правильные команды для PostgreSQL
        $pdo->exec("SET client_encoding = 'UTF8'");
        $pdo->exec("SET NAMES 'UTF8'");
    }
    return $pdo;
}
?>