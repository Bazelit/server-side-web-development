<?php
namespace MyProject\Controllers;

class ArticleController {
    
    public function edit($id) {
        // Здесь вы можете загрузить данные статьи из базы данных
        // Для примера используем тестовые данные
        $article = [
            'id' => $id,
            'title' => 'Статья #' . $id,
            'content' => 'Содержимое статьи #' . $id,
            'author' => 'Автор'
        ];
        
        // Проверяем, отправлена ли форма
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Обработка сохранения статьи
            $article['title'] = $_POST['title'] ?? '';
            $article['content'] = $_POST['content'] ?? '';
            $article['author'] = $_POST['author'] ?? '';
            
            // Здесь должна быть логика сохранения в БД
            // Пока просто перенаправляем или выводим сообщение об успехе
            $_SESSION['message'] = 'Статья успешно обновлена!';
            // header('Location: /article/' . $id);
        }
        
        include 'views/edit.php';
    }
    
    public function view($id) {
        // Загружаем данные статьи (тестовые данные)
        $article = [
            'id' => $id,
            'title' => 'Статья #' . $id,
            'content' => 'Содержимое статьи #' . $id,
            'author' => 'Автор',
            'date' => date('d.m.Y')
        ];
        
        include 'views/view.php';
    }
}
?>
