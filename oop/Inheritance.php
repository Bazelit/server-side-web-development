<?php

// БАЗОВЫЙ (РОДИТЕЛЬСКИЙ) КЛАСС
class Lesson
{
    // protected — видно в классе и в наследниках (но не снаружи)
    protected $title;
    protected $text;
    protected $homework;

    public function __construct(string $title, string $text, string $homework)
    {
        $this->title = $title;
        $this->text = $text;
        $this->homework = $homework;
    }

    // Геттеры для родительских свойств
    public function getTitle(): string { return $this->title; }
    public function getText(): string { return $this->text; }
    public function getHomework(): string { return $this->homework; }
}

// НАСЛЕДНИК — расширяет функционал родителя
class PaidLesson extends Lesson
{
    // НОВОЕ свойство — только у платного урока
    private $price;

    // КОНСТРУКТОР: вызывает родительский конструктор + добавляет свое
    public function __construct(string $title, string $text, string $homework, float $price)
    {
        // parent::__construct() — вызывает конструктор класса Lesson
        parent::__construct($title, $text, $homework);
        $this->price = $price;
    }

    // ГЕТТЕР для цены
    public function getPrice(): float
    {
        return $this->price;
    }

    // СЕТТЕР для изменения цены
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}

// СОЗДАЕМ ОБЪЕКТ ПЛАТНОГО УРОКА
$paidLesson = new PaidLesson(
    'Урок о наследовании в PHP',   // заголовок
    'Лол, кек, чебурек',           // текст
    'Ложитесь спать, утро вечера мудренее', // домашка
    99.90                           // цена
);

// Выводим ВСЮ структуру объекта
var_dump($paidLesson);

// Результат: объект содержит и родительские свойства (title, text, homework)
// И новое свойство price
?>