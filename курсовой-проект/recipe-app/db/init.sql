-- Schema for recipes project
DROP TABLE IF EXISTS recipes;
CREATE TABLE recipes (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  ingredients JSONB NOT NULL,
  steps TEXT,
  servings INTEGER DEFAULT 1,
  created_at TIMESTAMP DEFAULT now()
);

-- Sample data
INSERT INTO recipes (title, description, ingredients, steps, servings) VALUES
('Быстрый овощной суп', 'Легкий и быстрый суп для буднего дня',
  '[{"name":"Вода","qty":1000,"unit":"мл"},{"name":"Морковь","qty":2,"unit":"шт"},{"name":"Картофель","qty":3,"unit":"шт"},{"name":"Соль","qty":1,"unit":"ч.л."}]',
  '1. Нарежьте овощи.\n2. Варите 20 минут.\n3. Добавьте соль по вкусу.', 4
),
('Омлет с зеленью', 'Пышный омлет на завтрак',
  '[{"name":"Яйца","qty":3,"unit":"шт"},{"name":"Молоко","qty":50,"unit":"мл"},{"name":"Зелень","qty":10,"unit":"г"},{"name":"Соль","qty":0.5,"unit":"ч.л."}]',
  '1. Взбейте яйца с молоком и солью.\n2. Обжарьте на сковороде.\n3. Посыпьте зеленью.', 2
);
