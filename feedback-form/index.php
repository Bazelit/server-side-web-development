<?php
// index.php - Страница 1: Форма обратной связи
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма обратной связи</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <img
                    width="250"
                    class="logo"
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvp42kooFZKGZYiw4purSlyg0t9IGIeyBH3w&s"
                    alt="logo"
                />
                <h1 class="header-title">Форма обратной связи</h1>
                <h2>Выполнил Гумашян Давид Артакович 251-3210</h2>
                <div class="header-spacer"></div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main">
        <div class="form-container">
                <form id="feedbackForm" method="POST" action="https://httpbin.org/post">
                    <div class="form-group">
                        <label for="name">Имя пользователя *</label>
                        <input type="text" id="name" name="name" required placeholder="Введите ваше имя">
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail пользователя *</label>
                        <input type="email" id="email" name="email" required placeholder="example@mail.com">
                    </div>

                    <div class="form-group">
                        <label for="feedbackType">Тип обращения *</label>
                        <select id="feedbackType" name="feedbackType" required>
                            <option value="">-- Выберите тип --</option>
                            <option value="complaint">Жалоба</option>
                            <option value="suggestion">Предложение</option>
                            <option value="thanks">Благодарность</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Текст обращения *</label>
                        <textarea id="message" name="message" required placeholder="Введите ваше сообщение" rows="6"></textarea>
                    </div>

                    <div class="form-group">
                        <fieldset>
                            <legend>Вариант ответа</legend>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="response_method" value="sms">
                                    SMS
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="response_method" value="email">
                                    E-mail
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                        <a href="page2.php" class="btn btn-secondary">Перейти на страницу 2</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>Задание для самостоятельной работы</p>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>
