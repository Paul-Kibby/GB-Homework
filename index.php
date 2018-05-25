<?php
require_once 'applications/Twig/Autoloader.php';
require 'applications/config/db.php';
require 'applications/classes/Picture.php';

Twig_Autoloader::register();

$picture = new Picture($connection);

if( isset($_POST['submit']) and !empty($_FILES['upload_img']['name']) )
{
    if( $picture->upload($_FILES['upload_img']) )
    {
        header('Location: /');
        exit;
    } else
    {
        echo 'Во время добавления изображения возникла ошибка.';
    }
}

if( isset($_GET['delete']) and $_GET['delete'] != '' )
{
    if( $picture->delete($_GET['delete']) )
    {
        header('Location: /');
        exit;
    }
}

$picture->getCatalog();

try {
    $loader = new Twig_Loader_Filesystem('applications/templates');
    $twig = new Twig_Environment($loader);
    $template = $twig->loadTemplate('catalog.tmpl');

    $content = $template->render(array(
        'title' => 'Каталог',
        'catalog' => $picture->catalog
    ));
    echo $content;

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
