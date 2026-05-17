<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой блог</title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            Мой блог
        </td>
    </tr>
    <tr>
        <td>
            <?php foreach ($articles as $article): ?>
                <h2>
                    <a href="/article/<?php echo $article['id']; ?>" style="text-decoration: none; color: inherit;">
                        <?php echo htmlspecialchars($article['title']); ?>
                    </a>
                </h2>
                <p><?php echo htmlspecialchars($article['content']); ?></p>
                <a href="/article/<?php echo $article['id']; ?>/edit">✎ Редактировать</a>
                <hr>
            <?php endforeach; ?>
        </td>

        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/">Главная страница</a></li>
                <li><a href="/about-me">Обо мне</a></li>
                <li><a href="/bye/Друг">Сказать "Пока"</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>

</body>
</html>