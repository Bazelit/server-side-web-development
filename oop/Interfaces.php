<?php

// ИНТЕРФЕЙС — контракт. Любой класс, который его реализует,
// ОБЯЗАН иметь метод calculateSquare()
interface CalculateSquare
{
    public function calculateSquare(): float;
}

// КЛАСС ПРЯМОУГОЛЬНИК — реализует интерфейс
class Rectangle implements CalculateSquare
{
    private $width;
    private $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    // Обязательная реализация метода интерфейса
    public function calculateSquare(): float
    {
        return $this->width * $this->height;
    }
}

// КЛАСС КРУГ — тоже реализует интерфейс
class Circle implements CalculateSquare
{
    private $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    public function calculateSquare(): float
    {
        return pi() * pow($this->radius, 2);
    }
}

// КЛАСС ТРЕУГОЛЬНИК — НЕ реализует интерфейс
class Triangle
{
    private $base;
    private $height;

    public function __construct(float $base, float $height)
    {
        $this->base = $base;
        $this->height = $height;
    }

    public function getArea(): float
    {
        return 0.5 * $this->base * $this->height;
    }
}

// ФУНКЦИЯ ДЛЯ ПРОВЕРКИ И ВЫВОДА
function calculateAndPrintSquare($object)
{
    // get_class() — возвращает имя класса объекта (строка)
    $className = get_class($object);
    
    // instanceof — проверяет, реализует ли объект интерфейс
    if ($object instanceof CalculateSquare) {
        // Если реализует — считаем площадь
        echo "Объект класса {$className} имеет площадь: " . $object->calculateSquare() . "\n";
    } else {
        // Если нет — выводим сообщение
        echo "Объект класса {$className} не реализует интерфейс CalculateSquare.\n";
    }
}

// СОЗДАЕМ ОБЪЕКТЫ
$rectangle = new Rectangle(5, 10);
$circle = new Circle(7);
$triangle = new Triangle(4, 6);

// ПРОВЕРЯЕМ
calculateAndPrintSquare($rectangle); // Объект класса Rectangle имеет площадь: 50
calculateAndPrintSquare($circle);    // Объект класса Circle имеет площадь: 153.938...
calculateAndPrintSquare($triangle);  // Объект класса Triangle не реализует интерфейс
?>