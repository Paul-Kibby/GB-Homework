<?php
require "../config/db.php";

if( isset($_POST['id']) and isset($_POST['col']) )
{
    $result = mysqli_query($connection, "UPDATE `basket` SET `quantity` = ".$_POST['col']." WHERE `id` = ".$_POST['id']." ");
}