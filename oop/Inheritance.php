<?php

class Lesson
{
    protected $title;
    protected $text;
    protected $homework;

    public function __construct(string $title, string $text, string $homework)
    {
        $this->title = $title;
        $this->text = $text;
        $this->homework = $homework;
    }

    // Getters and setters can be added as needed
    public function getTitle(): string { return $this->title; }
    public function getText(): string { return $this->text; }
    public function getHomework(): string { return $this->homework; }
}

class PaidLesson extends Lesson
{
    private $price;

    public function __construct(string $title, string $text, string $homework, float $price)
    {
        parent::__construct($title, $text, $homework);
        $this->price = $price;
    }

    // Getter for price
    public function getPrice(): float
    {
        return $this->price;
    }

    // Setter for price
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}

// Create object with specified properties
$paidLesson = new PaidLesson(
    'Урок о наследовании в PHP',
    'Лол, кек, чебурек',
    'Ложитесь спать, утро вечера мудренее',
    99.90
);

var_dump($paidLesson);

?>