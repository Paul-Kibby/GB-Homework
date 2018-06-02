<?php

require 'application/config/db.php';

spl_autoload_register(function($classname) {
    require_once "application/controllers/{$classname}.php";
});

$page = 'home';
if( isset($_GET['page']) and $_GET['page'] != '' )
{
    $page = $_GET['page'];
}

$controller = new PageController($pdo);
$controller->Request($page);