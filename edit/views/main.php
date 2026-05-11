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
            <h2><a href="/article/1" style="text-decoration: none; color: inherit;">Статья 1</a></h2>
            <p>Всем привет, это текст первой статьи</p>
            <a href="/article/1/edit">✎ Редактировать</a>
            <hr>

            <h2><a href="/article/2" style="text-decoration: none; color: inherit;">Статья 2</a></h2>
            <p>Всем привет, это текст второй статьи</p>
            <a href="/article/2/edit">✎ Редактировать</a>
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