<?php

// КОНЦЕПЦИЯ: Абстрактный класс нельзя использовать напрямую. Он задает КАРКАС для других классов.
abstract class HumanAbstract
{
    // Приватное свойство — инкапсуляция. Никто снаружи не может его изменить.
    private $name;

    // Конструктор вызывается при создании объекта
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    // Обычный геттер — открывает доступ к приватному свойству
    public function getName(): string
    {
        return $this->name;
    }

    // Абстрактные методы НЕ ИМЕЮТ реализации здесь.
    // Зато наследники ОБЯЗАНЫ их реализовать сами.
    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;

    // Этот метод уже готов — он использует абстрактные методы.
    // Он не знает, на каком языке будет приветствие, но соберет фразу правильно.
    public function introduceYourself(): string
    {
        return $this->getGreetings() . '! ' .
               $this->getMyNameIs() . ' ' . $this->getName() . '.';
    }
}

// НАСЛЕДНИК №1: русский человек
class RussianHuman extends HumanAbstract
{
    // Обязаны реализовать оба абстрактных метода
    public function getGreetings(): string
    {
        return 'Привет';  // русское приветствие
    }

    public function getMyNameIs(): string
    {
        return 'Меня зовут';
    }
}

// НАСЛЕДНИК №2: английский человек
class EnglishHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return 'Hello';
    }

    public function getMyNameIs(): string
    {
        return 'My name is';
    }
}

// СОЗДАЕМ ОБЪЕКТЫ
$russian = new RussianHuman('Иван');
$english = new EnglishHuman('John');

// ВЫВОД — полиморфизм: одинаковый метод дает разный результат
echo $russian->introduceYourself(); // Привет! Меня зовут Иван.
echo "\n";
echo $english->introduceYourself(); // Hello! My name is John.

// ОТВЕТ НА ВОПРОС "ЗАЧЕМ АБСТРАКТНЫЙ КЛАСС?"
// Чтобы нельзя было написать new HumanAbstract() — только конкретных людей.
?>