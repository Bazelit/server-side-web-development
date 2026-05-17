<?php
namespace MyProject\Controllers;

class ArticleController {
    
    private function getConnection() {
        $dsn = 'pgsql:host=localhost;dbname=blog';
        $user = 'postgres';
        $password = '1234';
        
        $pdo = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
        return $pdo;
    }
    
    public function edit($id) {
        $pdo = $this->getConnection();
        
        // Обработка POST — сохранение изменений
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $pdo->prepare(
                'UPDATE articles SET title = :title, content = :content, author = :author WHERE id = :id'
            );
            $stmt->execute([
                ':title'   => $_POST['title']   ?? '',
                ':content' => $_POST['content'] ?? '',
                ':author'  => $_POST['author']  ?? '',
                ':id'      => $id,
            ]);
            
            header('Location: /article/' . $id);
            exit;
        }
        
        // Загружаем статью из БД
        $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$article) {
            header('HTTP/1.0 404 Not Found');
            echo '404 — Статья не найдена';
            return;
        }
        
        include 'views/edit.php';
    }
    
    public function view($id) {
        $pdo = $this->getConnection();
        
        $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$article) {
            header('HTTP/1.0 404 Not Found');
            echo '404 — Статья не найдена';
            return;
        }
        
        // Форматируем дату для отображения
        $article['date'] = date('d.m.Y', strtotime($article['date']));
        
        include 'views/view.php';
    }
}
?>