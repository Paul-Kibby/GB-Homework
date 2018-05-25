<?php
require_once 'applications/Twig/Autoloader.php';
require 'applications/config/db.php';
require 'applications/classes/Picture.php';

Twig_Autoloader::register();


if( isset($_GET['id']) and $_GET['id'] != '' )
{
    $picture = new Picture($connection);
    $img = $picture->getSingle($_GET['id']);
} else
{
    header('Location: /');
    exit;
}

try {
    $loader = new Twig_Loader_Filesystem('applications/templates');
    $twig = new Twig_Environment($loader);
    $template = $twig->loadTemplate('view.tmpl');

    $content = $template->render(array(
        'title' => 'Просмотр',
        'img' => $img
    ));
    echo $content;

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
