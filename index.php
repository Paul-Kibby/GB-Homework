<?php

require "applications/config/db.php";
require "applications/controllers/main.php";

if( $_GET['page'] != 'admin' )
{
    require "applications/views/main.tpl.php";
} else
{
    require "applications/views/adminMain.tpl.php";
}
