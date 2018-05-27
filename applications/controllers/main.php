<?php

require 'applications/models/models.php';

$user = checkAuth($connection);
if( isset($user['name']) ) // Проверка пользователя на авторизацию
{
    $logged = 'on';
}

if( $logged == 'on' )
{
    $basketCol = basketCol($connection, $user['id']);
}

if( $_GET['page'] == 'auth' ) // Если пользователь на странице авторизации/регистрации
{
    if( $logged == 'on' ) // Если авторизация прошла успешно
    {
        header('Location: index.php?page=lk');
        exit();
    }

    if( isset($_POST['submit_reg']) ) // Если нажата кнопка регистрации
    {
        $reg_result = registerAction($connection, $_POST['name'], $_POST['email'], $_POST['password'], $_POST['to_password']);

        if( $reg_result == 'E_PTP' )
        {
            echo 'Пароли не совпадают.';
        } else if( $reg_result == 'E_EMAIL' )
        {
            echo 'Пользователь с таким e-mail адресом уже зарегистрирован';
        } else if( $reg_result == 'E_NULL' )
        {
            echo 'Заполните все поля.';
        } else if ( $reg_result == 'E_SUCCESS' )
        {
            echo 'Успешная регистрация.';
        }
    }

    if( isset($_POST['submit_login']) ) // Если нажата кнопка входа
    {
        $login_result = loginAction($connection, $_POST['email'], $_POST['password']);

        if( $login_result == 'E_NULL' )
        {
            echo 'Заполните все поля.';
        } else if( $login_result == 'E_PASS' )
        {
            echo 'Логин либо пароль введены неверно.';
        } else if( $login_result == 'E_EMAIL' )
        {
            echo 'Логин либо пароль введены неверно.';
        } else if( $login_result == 'E_SUCCESS' )
        {
            header('Location: index.php?page=lk');
            exit();
        }
    }

    $content = 'applications/views/auth.tpl.php';
} else if( $_GET['page'] == 'lk' ) // Если пользователь на странице личного кабинета
{

    if( $logged != 'on' ) // Если пользователь не авторизован, и каким-то образом попал на страницу личного кабинета
    {
        header('Location: index.php?page=auth');
        exit();
    }

    if( isset($_POST['submit_change']) ) // Если нажата кнопка изменения личных данных пользователя
    {
        $changeResult = changeUserAction($connection, $_POST['name'], $_POST['email']);
       
        if( $changeResult )
        {
            header('Location: index.php?page=lk');
            exit();
        } else
        {
            echo 'Возникла ошибка при изменении данных.';
        }
    }

    $lkOrdersView = lkOrdersView($connection, $user['id']);

    $content = 'applications/views/lk.tpl.php';
} else if( $_GET['action'] == 'exit' ) // Если выбран пункт меню "Выход"
{
    $content = 'applications/views/catalog.tpl.php';
    exitAction();
} else if( $_GET['page'] == 'view' )
{
    if( isset($_GET['id']) )
    {
        $fullViewsContent = fullViews($connection, $_GET['id']);
        $content = 'applications/views/view.tpl.php';
    } else
    {
        header('Location: index.php');
        exit();
    }
} else if( $_GET['page'] == 'admin' ) // Если пользователь находится на странице "Панель администратора"
{
    if( $logged == 'on' and $user['admin'] == 1 ) // Проверка пользователя на авторизацию и на права администратора
    {
        if( $_GET['action'] == 'add' ) // Если пользователь находится на странице "Добавление товара"
        {
            if( isset($_POST['submit_add']) ) // Если пользователь нажал кнопку "Добавить"
            {
                $adminAddResult = adminAddAction($connection, $_POST['title'], $_POST['description'], $_POST['price'], $_POST['discount'], $_FILES);
                if( $adminAddResult )
                {
                    header('Location: index.php?page=admin');
                    exit();
                } else
                {
                    echo 'При добавлении товара возникли ошибки.';
                }
            }
            $content = 'applications/views/adminAdd.tpl.php';
        } else if( $_GET['action'] == 'change' ) // Если пользователь находится на странице "Редактирование товара"
        {
            if( isset($_POST['submit_change']) ) // Если нажата кнопка "Изменить"
            {
                $adminChangeResult = adminChangeAction($connection, $_POST['id'], $_POST['title'], $_POST['description'], $_POST['price'], $_POST['discount'], $_POST['img_old_full'], $_POST['img_old_small'], $_FILES);

                if( $adminChangeResult )
                {
                    header('Location: index.php?page=admin');
                    exit();
                } else
                {
                    echo 'При редактировании возникли ошибки.';
                }
            }

            if( isset($_GET['id']) ) // Если передан id товара - происходит выборка товара из БД и вставка данных в поля изменения
            {
                $changeCat = adminChangeViewAction($connection, $_GET['id']);
                $content = 'applications/views/adminChange.tpl.php';
            } else
            {
                header('Location: index.php?page=admin');
                exit();
            }
            
            
        } else if( $_GET['action'] == 'delete' ) // Если нажата кнопка "Удалить"
        {
            if( isset($_GET['id']) ) // Если передан id товара - вызов функции удаления товара
            {
                adminDeleteAction($connection, $_GET['id']);

                header('Location: index.php?page=admin');
                exit();
            }

            $content = 'applications/views/adminViews.tpl.php';
        } else if( $_GET['subpage'] == 'orders' )
        {
            if( $_GET['action'] == 'order_delete' and isset($_GET['id']) )
            {
                $adminOrdersDelResult = adminOrdersDeleteAction($connection, $_GET['id']);

                header('Location: index.php?page=admin&subpage=orders');
                exit();
            }

            $adminOrdersResult = adminOrders($connection);

            $content = 'applications/views/adminOrders.tpl.php';
        } else
        {
            $content = 'applications/views/adminViews.tpl.php';
        }
        
    } else // Если неавторизованный пользователь, либо пользователь без прав администратора попал на страницу "Панель администратора"
    {
        header('Location: index.php');
        exit();
    }
} else if( $_GET['page'] == 'reviews' ) // Если пользователь находится на странице "Отзывы"
{
    if( isset($_POST['submit_comment']) ) // Если пользователь нажал кнопку отправки отзыва
    {
        reviewAdd($connection, $user, $_POST['text']);

        header('Location: index.php?page=reviews');
        exit();
    }

    if( $_GET['action'] == 'delete' ) // Если пользователь нажал удалить отзыв
    {
        if(isset($_GET['id']))
        {
            reviewDelete($connection, $user, $_GET['id']);

            header('Location: index.php?page=reviews');
            exit();
        }
    }

    if( $logged == 'on' )
    {
        $echoCommentAdd = '
        <form action="index.php?page=reviews" method="POST">
            <p class="review_inp_title">Оставить отзыв</p>
            <textarea class="inp_text inp_text_review" name="text" required></textarea>
            <input class="button review_button" type="submit" name="submit_comment" value="Добавить отзыв">
        </form>
        ';
    } else
    {
        $echoCommentAdd = '<p class="echo_comment_add"><a href="index.php?page=auth">Авторизуйтесь</a>, чтобы оставить отзыв</p>';
    }

    $reviewView = reviewView($connection, $user, $logged);

    $content = 'applications/views/reviews.tpl.php';
} else if( $_GET['page'] == 'basket' ) // Если пользователь находится на странице "Корзина"
{
    if( $logged == 'on' ) // Проверка на авторизацию
    {
        if( $_GET['action'] == 'add' and isset($_GET['product_id']) ) // Если пользователь добавляет товар в корзину
        {
            $basketAddResult = basketAddAction($connection, $logged, $user['id'], $_GET['product_id']);
            if( $basketAddResult )
            {
                header('Location: index.php?page=basket');
                exit();
            }
        } else if( $_GET['action'] == 'delete' and isset($_GET['id']) ) // Если пользователь удаляет товар из корзины
        {
            $basketDeleteResult = basketDeleteAction($connection, $user['id'], $_GET['id']);
            if( $basketDeleteResult )
            {
                header('Location: index.php?page=basket');
                exit();
            }
        } 
        // else if( $_GET['action'] == 'change' and isset($_POST['id']) and isset($_POST['col']) ) // Если пользователь
        // {
        //     $basketChangeResult = basketChangeAction($connection, $user['id'], $_POST['id'], $_POST['col']);
        // }

        if( $basketCol == 0 ) // Проверка на количество товаров в корзине
        {
            $basketViewResult[1] = '<div class="basket_null_block"><p>Ваша корзина пуста</p></div>';
        } else
        {
            $basketViewResult = basketView($connection, $user['id']);
        }
    } else
    {
        header('Location: index.php?page=auth');
        exit();
    } 

    
    $content = 'applications/views/basket.tpl.php';
} else if( $_GET['page'] == 'order' ) // Если пользователь на странице "Оформление заказа"
{
    if( isset($_GET['id']) and isset($_GET['quantity']) )
    {
        $orderView = orderView($connection, $_GET['id'], $_GET['quantity']);

        if( isset($_POST['submit_order']) ) // Если нажата кнопка "Оформить заказ"
        {
            $orderAddActionResult = orderAddAction($connection, $user['id'], $_GET['id'], $_POST['name'], $_POST['email'], $_POST['address'], $_POST['phone'], $_POST['quantity']);
            
            header('Location: index.php?page=lk');
            exit();
        }
        $content = 'applications/views/order.tpl.php';
    } else
    {
        header('Location: index.php?page=basket');
        exit();
    }

} else
{
    $limit = 4;
    $offset = 0;
    if( isset($_GET['limit']) and $_GET['limit'] != '' )
    {
        $limit = $_GET['limit'];
    }

    $catalogContent = catalogViews($connection, $limit);

    $content = 'applications/views/catalog.tpl.php';
}

