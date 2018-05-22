<?php

abstract class Product {
    protected $name;
    protected static $unitPrice = 240;

    public function __construct($name)
    {
        $this->name = $name;
    }

    abstract public function getResult();
}

// Цифровой товар
class DigitalGoods extends Product {

    public function __construct($name)
    {
        parent::__construct($name);
    }

    private function getCost()
    {
        $cost = self::$unitPrice / 2;
        return $cost;
    }

    public function getResult()
    {
        return "Цифровой товар: {$this->name} | Стоимость: {$this->getCost()}<br><br>";
    }
}

// Штучный физический товар
class UnitGoods extends Product {

    public function __construct($name)
    {
        parent::__construct($name);
    }

    public function getResult()
    {
        return "Штучный товар: {$this->name} | Стоимость: ".self::$unitPrice."<br><br>";
    }
}

// Весовой товар
class WeightedGoods extends Product {

    private $kg;
    private $price;

    public function __construct($name, $kg, $price)
    {
        parent::__construct($name);
        $this->kg = $kg;
        $this->price = $price;
    }

    private function getCost()
    {
        $cost = $this->price * $this->kg;
        return $cost;
    }

    public function getResult()
    {
        return "Товар на вес: {$this->name} | Килограмм: {$this->kg} | Стоимость: {$this->getCost()}<br><br>";
    }
}