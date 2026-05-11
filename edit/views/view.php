<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($article['title']); ?> - Мой блог</title>
    <link rel="stylesheet" href="/styles/styles.css">
    <style>
        .article-header {
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .article-header h2 {
            margin: 0 0 10px 0;
        }
        
        .article-meta {
            color: #666;
            font-size: 14px;
        }
        
        .article-content {
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 15px;
        }
        
        .article-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .article-actions a {
            display: inline-block;
            margin-right: 15px;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .link-edit {
            background-color: #0066cc;
            color: white;
        }
        
        .link-edit:hover {
            background-color: #0052a3;
        }
        
        .link-back {
            background-color: #6c757d;
            color: white;
        }
        
        .link-back:hover {
            background-color: #5a6268;
        }
    </style>
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
            <div class="article-header">
                <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                <div class="article-meta">
                    Автор: <strong><?php echo htmlspecialchars($article['author']); ?></strong> | 
                    Дата: <strong><?php echo htmlspecialchars($article['date']); ?></strong>
                </div>
            </div>
            
            <div class="article-content">
                <?php echo nl2br(htmlspecialchars($article['content'])); ?>
            </div>
            
            <div class="article-actions">
                <a href="/article/<?php echo $article['id']; ?>/edit" class="link-edit">✎ Редактировать</a>
                <a href="/" class="link-back">← Вернуться на главную</a>
            </div>
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
