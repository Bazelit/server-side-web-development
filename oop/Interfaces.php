<?php

interface CalculateSquare
{
    public function calculateSquare(): float;
}

class Rectangle implements CalculateSquare
{
    private $width;
    private $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function calculateSquare(): float
    {
        return $this->width * $this->height;
    }
}

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

// Function to handle square calculation with proper messaging
function calculateAndPrintSquare($object)
{
    $className = get_class($object);
    
    if ($object instanceof CalculateSquare) {
        echo "Объект класса {$className} имеет площадь: " . $object->calculateSquare() . "\n";
    } else {
        echo "Объект класса {$className} не реализует интерфейс CalculateSquare.\n";
    }
}

// Example usage
$rectangle = new Rectangle(5, 10);
$circle = new Circle(7);
$triangle = new Triangle(4, 6);

calculateAndPrintSquare($rectangle); // Объект класса Rectangle имеет площадь: 50
calculateAndPrintSquare($circle);    // Объект класса Circle имеет площадь: 153.938...
calculateAndPrintSquare($triangle);  // Объект класса Triangle не реализует интерфейс CalculateSquare.

?>