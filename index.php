<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require 'classes/Car.php';

$car1 = new Car('Skoda', 'красный', 4);
echo $car1->getInfo();

$car2 = new TruckCar('MAN', 'синий', 2, '1.5 тонн');
echo $car2->getInfo();

/*

Задание #5
    Вывод: 1234
    Статическая переменная не теряет своего значения, когда выполнение программы выходит из этой области видимости
    Если же убрать static - получим: 1111


Задание #6
    Вывод: 1122
    Наследование приводит к тому, что создается новый метод

*/