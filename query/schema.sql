-- Создание таблицы пользователей
CREATE TABLE IF NOT EXISTS users (
    id   SERIAL PRIMARY KEY,
    nickname VARCHAR(100) NOT NULL
);

-- Создание таблицы статей
CREATE TABLE IF NOT EXISTS articles (
    id      SERIAL PRIMARY KEY,
    title   VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL REFERENCES users(id)
);

-- Тестовые данные
INSERT INTO users (id, nickname) VALUES (1, 'ivan'), (2, 'anna')
    ON CONFLICT DO NOTHING;

INSERT INTO articles (id, title, content, user_id) VALUES
    (1, 'Статья 1', 'Всем привет, это текст первой статьи',  1),
    (2, 'Статья 2', 'Всем привет, это текст второй статьи', 2)
    ON CONFLICT DO NOTHING;
