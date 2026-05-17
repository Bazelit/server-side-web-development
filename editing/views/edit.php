<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование статьи</title>
    <link rel="stylesheet" href="/styles/styles.css">
    <style>
        .edit-form {
            max-width: 800px;
            margin: 20px 0;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            min-height: 300px;
            resize: vertical;
        }
        
        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 5px rgba(0, 102, 204, 0.3);
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn-save {
            background-color: #28a745;
            color: white;
        }
        
        .btn-save:hover {
            background-color: #218838;
        }
        
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-cancel:hover {
            background-color: #5a6268;
        }
        
        .edit-title {
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            Редактирование статьи
        </td>
    </tr>
    <tr>
        <td>
            <div class="edit-title">
                <h2>Редактирование статьи #<?php echo htmlspecialchars($article['id']); ?></h2>
            </div>
            
            <form method="POST" class="edit-form">
                <div class="form-group">
                    <label for="title">Заголовок:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="author">Автор:</label>
                    <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($article['author']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="content">Содержимое:</label>
                    <textarea id="content" name="content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-save">Сохранить</button>
                    <a href="/" class="btn btn-cancel">Отмена</a>
                </div>
            </form>
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
