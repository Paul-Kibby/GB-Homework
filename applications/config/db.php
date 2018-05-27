<?php

$db_host     = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_name     = "store";

$connection = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if( !$connection )
{
    exit('Ошибка при подключении к базе данных.');
}
