<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Store</title>

    <link type="text/css" rel="stylesheet" href="public/css/style.css">
    <link type="text/css" rel="stylesheet" href="public/fonts/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <div class="header">
        <p class="header_title">Интернет магазин наручных часов</p>

        <ul class="header_menu">
            <li><a <?php if($content == 'applications/views/catalog.tpl.php'){echo 'class="active"';} ?> href="index.php">Главная</a></li>
            <li><a <?php if($content == 'applications/views/reviews.tpl.php'){echo 'class="active"';} ?> href="index.php?page=reviews">Отзывы</a></li>
            <li><a <?php if($content == 'applications/views/lk.tpl.php'){echo 'class="active"';} ?> href="index.php?page=lk">Личный кабинет</a></li>
            <li ><a class="basket_menu_a<?php if($content == 'applications/views/basket.tpl.php'){echo ' active';} ?>" href="index.php?page=basket">Корзина<?php if($basketCol > 0){ echo '<span class="basket_menu_col">'.$basketCol.'</span>'; } ?></a></li>
            <?php if( $logged == 'on' ) { if( $user['admin'] == 1 ) { echo '<li><a href="index.php?page=admin">AdminPanel</a></li>'; } echo '<li><a href="index.php?action=exit">Выход</a></li>';} ?>
        </ul>
    </div>

    <div class="wrapper">
    <?php
        require $content;
    ?>
    </div>
</body>
</html>