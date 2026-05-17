<?php
// views/article.php
// Переменные, доступные из ArticlesController::show():
//   $article          — массив с данными статьи
//   $authorNickname   — ник автора статьи
//   $comments         — массив комментариев к статье
?>

<h1><?php echo htmlspecialchars($article['title']); ?></h1>
<p><strong>Автор:</strong> <?php echo htmlspecialchars($authorNickname); ?></p>
<hr>
<div><?php echo nl2br(htmlspecialchars($article['content'])); ?></div>

<!-- ======================================================= -->
<!-- Список комментариев                                      -->
<!-- ======================================================= -->
<section style="margin-top: 40px;">
    <h2>Комментарии (<?php echo count($comments); ?>)</h2>

    <?php if (!empty($_GET['error']) && $_GET['error'] === 'empty'): ?>
        <p style="color: red;">Заполните все поля перед отправкой.</p>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
        <p style="color: #888;">Комментариев пока нет. Будьте первым!</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div id="comment<?php echo (int)$comment['id']; ?>"
                 style="border: 1px solid #ccc;
                        border-radius: 4px;
                        padding: 12px 16px;
                        margin-bottom: 12px;
                        background: #fafafa;">

                <div style="margin-bottom: 6px; font-size: 13px; color: #666;">
                    <strong><?php echo htmlspecialchars($comment['author_nickname']); ?></strong>
                    &nbsp;·&nbsp;
                    <?php echo htmlspecialchars($comment['created_at']); ?>
                    &nbsp;·&nbsp;
                    <a href="/comments/<?php echo (int)$comment['id']; ?>/edit">Редактировать</a>
                </div>

                <div><?php echo nl2br(htmlspecialchars($comment['text'])); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<!-- ======================================================= -->
<!-- Форма добавления комментария                             -->
<!-- POST /articles/{id}/comments                            -->
<!-- ======================================================= -->
<section style="margin-top: 30px;">
    <h2>Добавить комментарий</h2>

    <form method="POST" action="/articles/<?php echo (int)$article['id']; ?>/comments"
          style="max-width: 600px;">

        <div style="margin-bottom: 12px;">
            <label for="author_nickname" style="display:block; font-weight:bold; margin-bottom:4px;">
                Ваш ник:
            </label>
            <input type="text"
                   id="author_nickname"
                   name="author_nickname"
                   required
                   placeholder="Введите ник"
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; font-size:14px; box-sizing:border-box;">
        </div>

        <div style="margin-bottom: 12px;">
            <label for="comment_text" style="display:block; font-weight:bold; margin-bottom:4px;">
                Текст комментария:
            </label>
            <textarea id="comment_text"
                      name="text"
                      required
                      rows="4"
                      placeholder="Напишите ваш комментарий..."
                      style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; font-size:14px; box-sizing:border-box; resize:vertical;"></textarea>
        </div>

        <button type="submit"
                style="padding:10px 24px;
                       background-color: darkgreen;
                       color: white;
                       border: none;
                       border-radius: 4px;
                       font-size: 14px;
                       cursor: pointer;">
            Отправить комментарий
        </button>
    </form>
</section>
