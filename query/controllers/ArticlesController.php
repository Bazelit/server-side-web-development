<?php

class ArticlesController {

    private PDO $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'blog';
        $user = 'postgres';
        $password = '1234';

        $this->pdo = new PDO(
            "pgsql:host=$host;dbname=$dbname",
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    private function getArticleById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT id, title, content, user_id FROM articles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        return $article ?: null;
    }

    private function getAuthorNicknameById(int $userId): ?string {
        $stmt = $this->pdo->prepare('SELECT nickname FROM users WHERE id = :id');
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user['nickname'] : null;
    }

    public function show(int $id): void {
        $article = $this->getArticleById($id);

        if (!$article) {
            header('HTTP/1.0 404 Not Found');
            echo 'Статья не найдена';
            exit;
        }

        $authorNickname = $this->getAuthorNicknameById($article['user_id']);
        if (!$authorNickname) {
            $authorNickname = 'Неизвестный автор';
        }

        $pageTitle = $article['title'];
        $contentFile = 'views/article.php';

        include 'views/layout.php';
    }
}
