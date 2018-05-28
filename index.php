<?php

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

require "applications/config/db.php";
require "applications/controllers/main.php";

if( isset($_GET['page']) and $_GET['page'] == 'admin' )
{
    require "applications/views/adminMain.tpl.php";
} else
{
    require "applications/views/main.tpl.php";
}
