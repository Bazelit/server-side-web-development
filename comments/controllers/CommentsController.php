<?php
// controllers/CommentsController.php

class CommentsController {

    private $dataFile;

    public function __construct() {
        $this->dataFile = BASE_PATH . '/data/comments.json';
    }

    private function loadAll() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?: [];
    }

    private function saveAll(array $comments) {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents(
            $this->dataFile,
            json_encode(array_values($comments), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function findById($id) {
        foreach ($this->loadAll() as $comment) {
            if ((int)$comment['id'] === (int)$id) {
                return $comment;
            }
        }
        return null;
    }

    private function nextId() {
        $all = $this->loadAll();
        if (empty($all)) {
            return 1;
        }
        $maxId = max(array_column($all, 'id'));
        return $maxId + 1;
    }

    // POST /articles/123/comments
    public function store($articleId) {
        $text           = trim($_POST['text'] ?? '');
        $authorNickname = trim($_POST['author_nickname'] ?? '');

        if ($text === '' || $authorNickname === '') {
            header('Location: /articles/' . $articleId . '?error=empty');
            exit;
        }

        $newComment = [
            'id'              => $this->nextId(),
            'article_id'      => $articleId,
            'user_id'         => null,
            'author_nickname' => $authorNickname,
            'text'            => $text,
            'created_at'      => date('Y-m-d H:i:s'),
        ];

        $all   = $this->loadAll();
        $all[] = $newComment;
        $this->saveAll($all);

        header('Location: /articles/' . $articleId . '#comment' . $newComment['id']);
        exit;
    }

    // GET|POST /comments/456/edit
    public function edit($id) {
        $comment = $this->findById($id);

        if (!$comment) {
            header('HTTP/1.0 404 Not Found');
            echo 'Комментарий не найден';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newText = trim($_POST['text'] ?? '');

            if ($newText === '') {
                $error       = 'Текст комментария не может быть пустым';
                $pageTitle   = 'Редактирование комментария';
                $contentFile = BASE_PATH . '/views/comment_edit.php';
                include BASE_PATH . '/views/layout.php';
                exit;
            }

            $all = $this->loadAll();
            foreach ($all as &$c) {
                if ((int)$c['id'] === (int)$id) {
                    $c['text'] = $newText;
                    break;
                }
            }
            unset($c);
            $this->saveAll($all);

            header('Location: /articles/' . $comment['article_id'] . '#comment' . $id);
            exit;
        }

        // GET — показываем форму
        $pageTitle   = 'Редактирование комментария';
        $contentFile = BASE_PATH . '/views/comment_edit.php';
        include BASE_PATH . '/views/layout.php';
    }
}
