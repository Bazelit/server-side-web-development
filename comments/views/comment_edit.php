<?php
// views/comment_edit.php
// Переменные, доступные из CommentsController::edit():
//   $comment  — массив с данными комментария
//   $error    — текст ошибки (необязательно)
?>

<h1>Редактирование комментария #<?php echo (int)$comment['id']; ?></h1>

<p>
    <strong>Автор:</strong> <?php echo htmlspecialchars($comment['author_nickname']); ?>
    &nbsp;·&nbsp;
    <strong>Дата:</strong> <?php echo htmlspecialchars($comment['created_at']); ?>
</p>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<!-- POST /comments/{id}/edit -->
<form method="POST"
      action="/comments/<?php echo (int)$comment['id']; ?>/edit"
      style="max-width: 600px; margin-top: 20px;">

    <div style="margin-bottom: 16px;">
        <label for="comment_text" style="display:block; font-weight:bold; margin-bottom:6px;">
            Текст комментария:
        </label>
        <textarea id="comment_text"
                  name="text"
                  required
                  rows="6"
                  style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px; font-size:14px; box-sizing:border-box; resize:vertical;"><?php echo htmlspecialchars($comment['text']); ?></textarea>
    </div>

    <div style="display:flex; gap:12px;">
        <button type="submit"
                style="padding:10px 24px;
                       background-color: darkgreen;
                       color: white;
                       border: none;
                       border-radius: 4px;
                       font-size: 14px;
                       cursor: pointer;">
            Сохранить
        </button>

        <a href="/articles/<?php echo (int)$comment['article_id']; ?>#comment<?php echo (int)$comment['id']; ?>"
           style="padding:10px 24px;
                  background-color: #888;
                  color: white;
                  border-radius: 4px;
                  font-size: 14px;
                  text-decoration: none;
                  display:inline-block;">
            Отмена
        </a>
    </div>
</form>
