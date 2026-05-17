<?php
// controllers/ArticlesController.php

class ArticlesController {

    private function getArticleById($id) {
        $articles = [
            1 => [
                'id'      => 1,
                'title'   => 'Статья 1',
                'content' => 'Всем привет, это текст первой статьи',
                'user_id' => 1,
            ],
            2 => [
                'id'      => 2,
                'title'   => 'Статья 2',
                'content' => 'Всем привет, это текст второй статьи',
                'user_id' => 2,
            ],
        ];

        return isset($articles[$id]) ? $articles[$id] : null;
    }

    private function getAuthorNicknameById($userId) {
        $users = [
            1 => ['id' => 1, 'nickname' => 'ivan'],
            2 => ['id' => 2, 'nickname' => 'anna'],
        ];

        return isset($users[$userId]) ? $users[$userId]['nickname'] : null;
    }

    private function getCommentsByArticleId($articleId) {
        $all = $this->loadComments();
        $result = [];
        foreach ($all as $comment) {
            if ((int)$comment['article_id'] === (int)$articleId) {
                $result[] = $comment;
            }
        }
        return $result;
    }

    private function loadComments() {
        $file = BASE_PATH . '/data/comments.json';
        if (!file_exists($file)) {
            return [];
        }
        $json = file_get_contents($file);
        return json_decode($json, true) ?: [];
    }

    public function show($id) {
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

        $comments    = $this->getCommentsByArticleId($id);
        $pageTitle   = $article['title'];
        $contentFile = BASE_PATH . '/views/article.php';

        include BASE_PATH . '/views/layout.php';
    }
}
