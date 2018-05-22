<?php

require 'classes/Product.php';
require 'classes/Singleton.php';

$product1 = new DigitalGoods('Программное обеспечение');
echo $product1->getResult();

$product2 = new UnitGoods('Клавиатура');
echo $product2->getResult();

$product3 = new WeightedGoods('Сахар', 4, 80);
echo $product3->getResult();


Singleton::getInstance();
