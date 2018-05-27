<?php

require "../config/db.php";

if( isset($_POST['id']) and isset($_POST['value']) )
{
    $result = mysqli_query($connection, "UPDATE `orders` SET `status` = ".$_POST['value']." WHERE `id` = ".$_POST['id']." ");
}