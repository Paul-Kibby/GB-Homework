<?php

require 'application/lib/Dev.php';

spl_autoload_register(function($class)
{
    $path = str_replace('\\', '/', $class.'.php');
    if( file_exists($path) )
    {
        require_once $path;
    }
});

$router = new application\core\Router;
$router->run();