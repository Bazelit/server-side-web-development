-- Create database
CREATE DATABASE IF NOT EXISTS phonebook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE phonebook;

-- Create contacts table
CREATE TABLE IF NOT EXISTS contacts (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    surname  VARCHAR(100) NOT NULL,
    name     VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) DEFAULT '',
    gender   ENUM('мужской','женский') DEFAULT 'мужской',
    date     DATE DEFAULT NULL,
    phone    VARCHAR(50)  DEFAULT '',
    location VARCHAR(255) DEFAULT '',
    email    VARCHAR(150) DEFAULT '',
    comment  TEXT         DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data (optional)
INSERT INTO contacts (surname, name, lastname, gender, date, phone, location, email, comment) VALUES
('Иванов',   'Иван',    'Иванович',   'мужской', '1990-05-15', '+7 999 123-45-67', 'Москва',       'ivanov@example.com',   'Коллега'),
('Петрова',  'Мария',   'Сергеевна',  'женский', '1985-11-23', '+7 999 234-56-78', 'Санкт-Петербург', 'petrova@example.com', 'Друг'),
('Сидоров',  'Алексей', 'Петрович',   'мужской', '1978-03-02', '+7 999 345-67-89', 'Казань',       'sidorov@example.com',  ''),
('Козлова',  'Анна',    'Николаевна', 'женский', '1995-07-18', '+7 999 456-78-90', 'Новосибирск',  'kozlova@example.com',  'Клиент'),
('Новиков',  'Дмитрий', 'Александрович','мужской','2000-01-09','+7 999 567-89-01','Екатеринбург', 'novikov@example.com',  '');
