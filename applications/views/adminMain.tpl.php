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

    <div class="admin_topbar">
        <p class="admin_title">Панель Администратора</p>
    </div>

    <div class="admin_sidebar">
        <ul class="admin_menu">
            <li><a <?php if( $content == 'applications/views/adminAdd.tpl.php' ){ echo 'class="admin_active"'; } ?> href="index.php?page=admin&action=add"><i class="fa fa-plus adm_ico" aria-hidden="true"></i> Добавить товар</a></li>
            <li><a <?php if( $content == 'applications/views/adminViews.tpl.php' ){ echo 'class="admin_active"'; } ?> href="index.php?page=admin"><i class="fa fa-list adm_ico" aria-hidden="true"></i> Товары</a></li>
            <li><a <?php if( $content == 'applications/views/adminOrders.tpl.php' ){ echo 'class="admin_active"'; } ?> href="index.php?page=admin&subpage=orders"><i class="fa fa-th-list adm_ico" aria-hidden="true"></i> Заказы</a></li>
            <li><a href="index.php"><i class="fa fa-sign-out adm_ico" aria-hidden="true"></i> Выход</a></li>
        </ul>
    </div>

    <div class="admin_wrapper">
    <?php
        require $content;
    ?>
    </div>

    <script src="public/js/jquery-3.3.1.min.js"></script>
    <script src="public/js/core.js"></script>
    <script src="public/js/orders.js"></script>
</body>
</html>