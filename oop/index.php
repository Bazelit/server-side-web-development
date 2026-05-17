<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Демонстрация ООП в PHP</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .example {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 5px solid #4CAF50;
        }
        .example h2 {
            margin-top: 0;
            color: #333;
        }
        .principle {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        .code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 14px;
        }
        .output {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            border-left: 3px solid #2196F3;
        }
        hr {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>📚 Объектно-Ориентированное Программирование в PHP</h1>
    <p>Четыре основных принципа: Абстракция, Инкапсуляция, Наследование, Полиморфизм</p>
    <hr>

    <!-- 1. АБСТРАКЦИЯ -->
    <div class="example">
        <div class="principle">🔷 АБСТРАКЦИЯ</div>
        <h2>Абстрактный класс Human</h2>
        <div class="code">
            <pre>
abstract class HumanAbstract {
    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;
    
    public function introduceYourself(): string {
        return $this->getGreetings() . '! ' .
               $this->getMyNameIs() . ' ' . $this->getName() . '.';
    }
}

class RussianHuman extends HumanAbstract {
    public function getGreetings(): string { return 'Привет'; }
    public function getMyNameIs(): string { return 'Меня зовут'; }
}

class EnglishHuman extends HumanAbstract {
    public function getGreetings(): string { return 'Hello'; }
    public function getMyNameIs(): string { return 'My name is'; }
}
            </pre>
        </div>
        <div class="output">
            <strong>📤 Результат:</strong><br>
            <?php
            require_once 'Abstract.php';
            ?>
        </div>
        <p><strong>Объяснение:</strong> Абстрактный класс задаёт общий интерфейс, но конкретная реализация зависит от наследника.</p>
    </div>

    <!-- 2. ИНКАПСУЛЯЦИЯ -->
    <div class="example">
        <div class="principle">🔒 ИНКАПСУЛЯЦИЯ</div>
        <h2>Класс Cat с приватными свойствами</h2>
        <div class="code">
            <pre>
class Cat {
    private $name;   // Приватное свойство
    private $color;
    
    public function getColor(): string {
        return $this->color;  // Доступ через геттер
    }
    
    public function sayHello(): string {
        return "Meow! My name is {$this->name}. I am {$this->color}.";
    }
}
            </pre>
        </div>
        <div class="output">
            <strong>📤 Результат:</strong><br>
            <?php
            require_once 'Encapsulation.php';
            ?>
        </div>
        <p><strong>Объяснение:</strong> Свойства <code>private</code> скрыты от внешнего доступа. Для чтения используется публичный метод <code>getColor()</code>.</p>
    </div>

    <!-- 3. НАСЛЕДОВАНИЕ -->
    <div class="example">
        <div class="principle">🌳 НАСЛЕДОВАНИЕ</div>
        <h2>PaidLesson наследует Lesson</h2>
        <div class="code">
            <pre>
class Lesson {
    protected $title, $text, $homework;
}

class PaidLesson extends Lesson {
    private $price;
    
    public function __construct(...) {
        parent::__construct(...);  // Вызов родительского конструктора
    }
}
            </pre>
        </div>
        <div class="output">
            <strong>📤 Результат (var_dump объекта PaidLesson):</strong><br>
            <pre><?php
            require_once 'Inheritance.php';
            ?></pre>
        </div>
        <p><strong>Объяснение:</strong> <code>PaidLesson</code> наследует все свойства и методы <code>Lesson</code>, добавляя своё — <code>$price</code>.</p>
    </div>

    <!-- 4. ПОЛИМОРФИЗМ -->
    <div class="example">
        <div class="principle">🎭 ПОЛИМОРФИЗМ</div>
        <h2>Интерфейс CalculateSquare</h2>
        <div class="code">
            <pre>
interface CalculateSquare {
    public function calculateSquare(): float;
}

class Rectangle implements CalculateSquare { ... }
class Circle implements CalculateSquare { ... }
class Triangle { ... }  // Не реализует интерфейс
            </pre>
        </div>
        <div class="output">
            <strong>📤 Результат:</strong><br>
            <?php
            require_once 'Interfaces.php';
            ?>
        </div>
        <p><strong>Объяснение:</strong> Разные классы (<code>Rectangle</code>, <code>Circle</code>) реализуют один интерфейс. Функция работает с любым объектом, реализующим <code>CalculateSquare</code>.</p>
    </div>

    <hr>
    <footer>
        <p>✅ Все четыре принципа ООП продемонстрированы на работающих примерах.</p>
    </footer>
</body>
</html>