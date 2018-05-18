<?php

// Задание #1, #2, #3, #4
class Car {
    public $model; // Модель авто
    public $color; // Цвет
    public $seat; // Количество мест

    public function __construct($model, $color, $seat)
    {
        $this->model = $model;
        $this->color = $color;
        $this->seat = $seat;
    }

    public function getInfo()
    {
        $result = "<br>Модель: {$this->model}<br>Цвет: {$this->color}<br>Количество мест: {$this->seat}<br>";
        return $result;
    }
}

class TruckCar extends Car {
    public $capacity; // Грузоподъёмность

    public function __construct($model, $color, $seat, $capacity)
    {
        parent::__construct($model, $color, $seat);
        $this->capacity = $capacity;
    }

    public function getInfo()
    {
        $result = parent::getInfo();
        $result .= "Грузоподъёмность: {$this->capacity}";
        return $result;
    }
}