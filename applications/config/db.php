<?php

$connection = mysqli_connect('127.0.0.1', 'root', '', 'img');

if( !$connection )
{
    exit('Не удалось подключиться к БД');
}