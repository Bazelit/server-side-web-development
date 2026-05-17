<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Мой блог'); ?></title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            <?php echo htmlspecialchars($pageTitle ?? 'Мой блог'); ?>
        </td>
    </tr>
    <tr>
        <td class="content">
            <?php if (!empty($contentFile) && file_exists($contentFile)): ?>
                <?php include $contentFile; ?>
            <?php else: ?>
                <p>Контент не найден.</p>
            <?php endif; ?>
        </td>
        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/">Главная страница</a></li>
                <li><a href="/about-me">Обо мне</a></li>
                <li><a href="/bye/Друг">Сказать «Пока»</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>

</body>
</html>
