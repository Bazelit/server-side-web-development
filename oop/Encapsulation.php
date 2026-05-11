<?php

class Cat
{
    private $name;
    private $color; // Made private

    public function __construct(string $name, string $color) // Added color parameter
    {
        $this->name = $name;
        $this->color = $color;
    }

    public function sayHello(): string
    {
        return "Meow! My name is {$this->name}. I am {$this->color}.";
    }

    // Getter for color property
    public function getColor(): string
    {
        return $this->color;
    }
}

// Example usage
$cat = new Cat('Murka', 'ginger');
echo $cat->sayHello(); // Meow! My name is Murka. I am ginger.
echo "\n";
echo $cat->getColor(); // ginger

?>