<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Мой блог'); ?></title>
    <link rel="stylesheet" href="/styles/styles.css">
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f3f3; }
        .layout { width: 100%; max-width: 1200px; margin: 0 auto; border-collapse: collapse; }
        .header { background: #2c3e50; color: white; padding: 24px; font-size: 24px; }
        .content { background: white; padding: 24px; vertical-align: top; }
        .sidebar { background: #fafafa; padding: 24px; vertical-align: top; }
        .footer { background: #2c3e50; color: white; text-align: center; padding: 14px; font-size: 14px; }
        .sidebarHeader { font-weight: bold; margin-bottom: 12px; }
        .sidebar ul { list-style: none; padding-left: 0; }
        .sidebar li { margin-bottom: 10px; }
        .sidebar a { color: #2c3e50; text-decoration: none; }
        .sidebar a:hover { text-decoration: underline; }
    </style>
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
