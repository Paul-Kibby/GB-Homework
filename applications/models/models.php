<?php

function debug($var) // Для отладки (временно)
{
    echo var_dump($var);
}

function generateSalt($length=30) // Генерация salt
{ 
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  

    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;
}

function registerAction($connection, $name, $email, $password, $to_password) // Регистрация
{
    if( $name != '' and $email != '' and $password != '' and $to_password != '' )
    {
        $name = trim(strip_tags($name));
        $email = trim(strip_tags($email));
        // $password = trim(strip_tags($password));
        // $to_password = trim(strip_tags($to_password));

        $result = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '".$email."';");
        if( mysqli_num_rows($result) == 0 )
        {
            if( $password === $to_password )
            {
                $salt = generateSalt(30);
                $password = md5($password.$salt);

                $query = "INSERT INTO `users` (`name`, `email`, `password`, `salt`) VALUES ('$name', '$email', '$password', '$salt');";
                $res = mysqli_query($connection, $query);

                return 'E_SUCCESS';
            } else
            {
                return 'E_PTP'; // Пароли не совпадают
            }
        } else
        {
            return 'E_EMAIL'; // Пользователь с таким e-mail адресом уже зарегистрирован
        }
    } else
    {
        return 'E_NULL'; // Пустые поля
    }
    
}

function loginAction($connection, $email, $password) // Авторизация
{
    if( $email != '' and $password != '' )
    {
        $email = trim(strip_tags($email));
        // $password = trim(strip_tags($password));

        $result = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '".$email."';");

        if( mysqli_num_rows($result) > 0 )
        {
            $data = mysqli_fetch_assoc($result);

            $password = md5($password.$data['salt']);
    
            if( $password == $data['password'] )
            {
                setcookie('u_id', $data['id'], time() + (3600 * 24 * 4) );
                setcookie('u_email', $data['email'], time() + (3600 * 24 * 4) );
                setcookie('u_password', $data['password'], time() + (3600 * 24 * 4) );
                return 'E_SUCCESS'; // Успешно
            } else
            {
                return 'E_PASS'; // Неверный пароль
            }
        } else
        {
            return 'E_EMAIL'; // Неверный e-mail
        }
        
    } else
    {
        return 'E_NULL'; // Пустые поля
    }
}

function checkAuth($connection) // Проверка авторизации
{
    if( isset($_COOKIE['u_id']) and isset($_COOKIE['u_email']) and isset($_COOKIE['u_password']) )
    {
        $result = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '".$_COOKIE['u_id']."' LIMIT 1;");
        $user = mysqli_fetch_assoc($result);

        if( ($user['id'] == $_COOKIE['u_id']) and ($user['email'] == $_COOKIE['u_email']) and ($user['password'] == $_COOKIE['u_password']) )
        {
            return $user;
        } else
        {
            setcookie('u_id', "");
            setcookie('u_email', "");
            setcookie('u_password', "");
            return 'E_CHECK';
        }
    } else
    {
        return 'E_CHECK';
    }
    
}

function changeUserAction($connection, $name, $email) // Изменение данных пользователя
{
    $name = trim(strip_tags($name));
    $email = trim(strip_tags($email));

    $result = mysqli_query($connection, "UPDATE `users` SET `name` = '$name', `email` = '$email' WHERE `email` = '".$_COOKIE['u_email']."';");

    setcookie('u_email', $email, time() + (3600 * 24 * 4) );

    return $result;
}

function exitAction() // Выход из аккаунта
{
    setcookie('u_id', "");
    setcookie('u_email', "");
    setcookie('u_password', "");

    header('Location: index.php');
    exit();
}

function createThumbnail($path, $save, $width, $height) // Создание уменьшенного изображения
{
  $info = getimagesize($path); //получаем размеры картинки и ее тип
  $size = array($info[0], $info[1]); //закидываем размеры в массив
  //В зависимости от расширения картинки вызываем соответствующую функцию
  if ($info['mime'] == 'image/png') {
    $src = imagecreatefrompng($path); //создаём новое изображение из файла
  } else if ($info['mime'] == 'image/jpeg') {
    $src = imagecreatefromjpeg($path);
  } else if ($info['mime'] == 'image/gif') {
    $src = imagecreatefromgif($path);
  } else {
    return false;
  }
  $thumb = imagecreatetruecolor($width, $height); //возвращает идентификатор изображения, представляющий черное изображение заданного размера
  $src_aspect = $size[0] / $size[1]; //отношение ширины к высоте исходника
  $thumb_aspect = $width / $height; //отношение ширины к высоте аватарки
  if($src_aspect < $thumb_aspect) { //узкий вариант (фиксированная ширина) $scale = $width / $size[0]; $new_size = array($width, $width / $src_aspect); $src_pos = array(0, ($size[1] * $scale - $height) / $scale / 2); //Ищем расстояние по высоте от края картинки до начала картины после обрезки } else if ($src_aspect > $thumb_aspect) {
  //широкий вариант (фиксированная высота)
    $scale = $height / $size[1];
    $new_size = array($height * $src_aspect, $height);
    $src_pos = array(($size[0] * $scale - $width) / $scale / 2, 0); //Ищем расстояние по ширине от края картинки до начала картины после обрезки
  } else {
  //другое
    $new_size = array($width, $height);
    $src_pos = array(0,0);
  }
  $new_size[0] = max($new_size[0], 1);
  $new_size[1] = max($new_size[1], 1);
  imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);
  //Копирование и изменение размера изображения с ресемплированием
  if($save === false) {
    return imagepng($thumb); //Выводит JPEG/PNG/GIF изображение
  } else {
    return imagepng($thumb, $save);//Сохраняет JPEG/PNG/GIF изображение
  }
}

function adminAddAction($connection, $title, $description, $price, $discount, $imgFull) // Добавление товара
{
    $title = trim(strip_tags($title));
    $description = trim(strip_tags($description));
    $price = trim(strip_tags($price));
    $discount = trim(strip_tags($discount));

    if( $title != '' and $description != '' and $price != '' and $discount != '' )
    {
        if( !empty($imgFull['upload_img']['name']) )
        {
            $path_info = pathinfo($imgFull['upload_img']['name']);
            $new_name = date('d-m-Y_H-i-s') . "." . $path_info['extension'];

            $path = "public/img/imgFull/".$new_name;
            $save = "public/img/imgSmall/".$new_name;
            $width = 134;
            $height = 200;

            move_uploaded_file($imgFull['upload_img']['tmp_name'], $path);
            createThumbnail($path, $save, $width, $height);

            $result = mysqli_query($connection, "INSERT INTO `catalog` (`title`, `description`, `price`, `discount`, `img_full`, `img_small`) VALUES ('$title', '$description', '$price', '$discount', '$path', '$save');");
            return true;
        } else
        {
            return false;
        }

    } else
    {
        return false;
    }
}

function catalogViews($connection, $limit=4) // Вывод товаров на главную страницу
{
    $catalogContent = '';

    $countRes = mysqli_query($connection, "SELECT COUNT(`id`) FROM `catalog`");
    $cR = mysqli_fetch_array($countRes);
    $result = mysqli_query($connection, "SELECT * FROM `catalog` ORDER BY `id` DESC LIMIT $limit OFFSET 0");

    $col = mysqli_num_rows($result);
    if( $col < $cR[0] )
    {
        $l = $limit + 4;
        $pag = '<a class="catalog-pag-link" href="index.php?limit='.$l.'">Ещё</a>';
    } else
    {
        $pag = '';
    }

    while( $catalog = mysqli_fetch_assoc($result) )
    {
        if( $catalog['discount'] > 0 )
        {
            $discount = ($catalog['price'] * (100 - $catalog['discount'])) / 100;
            $catalogContent .= '
            <div class="catalog">
                <a class="catalog_link" href="index.php?page=view&id='.$catalog['id'].'">
                    <img class="catalog_img" src="'.$catalog['img_small'].'" alt="часы">
                    <p class="catalog_price_discount_text">Скидка '.$catalog['discount'].'%!</p>
                    <p class="catalog_price_discount">'.number_format($discount, 0, ',', ' ').' руб.</p>
                    <p class="catalog_price_cross">'.number_format($catalog['price'], 0, ',', ' ').' руб.</p>
                    <p class="catalog_title">'.$catalog['title'].'</p>
                </a>
                <a class="catalog_button" href="index.php?page=view&id='.$catalog['id'].'">Купить</a>
            </div>
            ';
        } else
        {
            $catalogContent .= '
            <div class="catalog">
                <a class="catalog_link" href="index.php?page=view&id='.$catalog['id'].'">
                    <img class="catalog_img" src="'.$catalog['img_small'].'" alt="часы">
                    <p class="catalog_price">'.number_format($catalog['price'], 0, ',', ' ').' руб.</p>
                    <p class="catalog_title">'.$catalog['title'].'</p>
                </a>
                <a class="catalog_button" href="index.php?page=view&id='.$catalog['id'].'">Купить</a>
            </div>
            ';
        }
    }

    $return[0] = $catalogContent;
    $return[1] = $pag;
    return $return;
}

function adminCatalogViews($connection) // Вывод товаров в панель администратора
{
    $catalogContent = '';
    $result = mysqli_query($connection, "SELECT * FROM `catalog` ORDER BY `id` DESC");
    while( $catalog = mysqli_fetch_assoc($result) )
    {
        if( $catalog['discount'] > 0 )
        {
            $discount = ($catalog['price'] * (100 - $catalog['discount'])) / 100;
            $catalogContent .= '
            <div class="catalog">
                <a class="catalog_link" href="index.php?page=view&id='.$catalog['id'].'">
                    <img class="catalog_img" src="'.$catalog['img_small'].'" alt="часы">
                    <p class="catalog_price_discount_text">Скидка '.$catalog['discount'].'%!</p>
                    <p class="catalog_price_discount">'.number_format($discount, 0, ',', ' ').' руб.</p>
                    <p class="catalog_price_cross">'.number_format($catalog['price'], 0, ',', ' ').' руб.</p>
                    <p class="catalog_title">'.$catalog['title'].'</p>
                </a>
                <a class="catalog_button2" href="index.php?page=admin&action=change&id='.$catalog['id'].'">Изменить</a>
                <a id="'.$catalog['id'].'" class="catalog_button3 delete_yes" href="#">Удалить</a>
            </div>
            ';
        } else
        {
            $catalogContent .= '
            <div class="catalog">
                <a class="catalog_link" href="index.php?page=view&id='.$catalog['id'].'">
                    <img class="catalog_img" src="'.$catalog['img_small'].'" alt="часы">
                    <p class="catalog_price">'.number_format($catalog['price'], 0, ',', ' ').' руб.</p>
                    <p class="catalog_title">'.$catalog['title'].'</p>
                </a>
                <a class="catalog_button2" href="index.php?page=admin&action=change&id='.$catalog['id'].'">Изменить</a>
                <a id="'.$catalog['id'].'" class="catalog_button3 delete_yes" href="#">Удалить</a>
            </div>
            ';
        }
    }
    return $catalogContent;
}

function adminChangeViewAction($connection, $id) // Получение данных о товаре (страница редактирования товара)
{
    $res = mysqli_query($connection, "SELECT * FROM `catalog` WHERE `id` = $id");
    $changeCat = mysqli_fetch_assoc($res);

    return $changeCat;
}

function adminChangeAction($connection, $id, $title, $description, $price, $discount, $imgOldFull, $imgOldSmall, $imgFull) // Редактирование товара
{
    $title = trim(strip_tags($title));
    $description = trim(strip_tags($description));
    $price = trim(strip_tags($price));
    $discount = trim(strip_tags($discount));

    if( $title != '' and $description != '' and $price != '' and $discount != '' )
    {
        if( !empty($imgFull['upload_img']['name']) )
        {
            $path_info = pathinfo($imgFull['upload_img']['name']);
            $new_name = date('d-m-Y_H-i-s') . "." . $path_info['extension'];

            $path = "public/img/imgFull/".$new_name;
            $save = "public/img/imgSmall/".$new_name;
            $width = 134;
            $height = 200;

            move_uploaded_file($imgFull['upload_img']['tmp_name'], $path);
            createThumbnail($path, $save, $width, $height);

            if( file_exists($imgOldFull) and file_exists($imgOldSmall) )
            {
                unlink($imgOldFull);
                unlink($imgOldSmall);
            }

            $result = mysqli_query($connection, "UPDATE `catalog` SET `title` = '$title', `description` = '$description', `price` = '$price', `discount` = '$discount', `img_full` = '$path', `img_small` = '$save'  WHERE `id` = $id");
            
        } else
        {
            $result = mysqli_query($connection, "UPDATE `catalog` SET `title` = '$title', `description` = '$description', `price` = '$price', `discount` = '$discount' WHERE `id` = $id");
        }

        if( $result )
        {
            return true;
        }

    } else
    {
        return false;
    }
}

function adminDeleteAction($connection, $id) // Удаление товара
{
    $res = mysqli_query($connection, "SELECT `img_small`, `img_full` FROM `catalog` WHERE `id` = $id");
    $deleteInp = mysqli_fetch_assoc($res);

    $result = mysqli_query($connection, "DELETE FROM `catalog` WHERE `id` = $id");

    if( $result )
    {
        if( file_exists($deleteInp['img_small']) and file_exists($deleteInp['img_full']) )
        {
            unlink($deleteInp['img_small']);
            unlink($deleteInp['img_full']);
        }
        return true;
    }
}

function adminOrders($connection)
{
    $orderView = '<tbody>';
    $result = mysqli_query($connection, "SELECT * FROM `orders` ORDER BY `id` DESC");

    while( $order = mysqli_fetch_assoc($result) )
    {
        if( $order['status'] == 0 )
        {
            $orderSelect = '<select id="'.$order['id'].'" class="order_status"><option value="0" selected>Новый</option><option value="1">Выполняется</option><option value="2">Выполнен</option></select>';
        } else if( $order['status'] == 1 )
        {
            $orderSelect = '<select id="'.$order['id'].'" class="order_status"><option value="0">Новый</option><option value="1" selected>Выполняется</option><option value="2">Выполнен</option></select>';
        } else
        {
            $orderSelect = '<select id="'.$order['id'].'" class="order_status"><option value="0">Новый</option><option value="1">Выполняется</option><option value="2" selected>Выполнен</option></select>';
        }
        
        $orderView .= '<tr><td>'.$order['name'].'</td><td>'.$order['email'].'</td><td>'.$order['address'].'</td><td>'.$order['phone'].'</td><td><a href="index.php?page=view&id='.$order['product_id'].'">'.$order['product_name'].'</a></td><td>'.$order['quantity'].'</td><td>'.number_format($order['price'], 0, ',', ' ').'</td><td>'.$orderSelect.'</td><td><a class="order_delete" href="index.php?page=admin&subpage=orders&action=order_delete&id='.$order['id'].'"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td></tr>';
    }
    $orderView .= '</tbody>';

    return $orderView;
}

function adminOrdersDeleteAction($connection, $id)
{
    if( $id != '' )
    {
        $result = mysqli_query($connection, "DELETE FROM `orders` WHERE `id` = $id");
    }
    return $result;
}

function fullViews($connection, $id) // Генерация главной страницы конкретного товара
{
    $fullViewsContent = '';
    $id = (int)$id;

    $result = mysqli_query($connection, "SELECT * FROM `catalog` WHERE `id` = $id");

    if( mysqli_num_rows($result) > 0 )
    {
        $catalogView = mysqli_fetch_assoc($result);

        $catalogView['description'] = nl2br($catalogView['description']);
        
        if( $catalogView['discount'] > 0 )
        {
            $discount = ($catalogView['price'] * (100 - $catalogView['discount'])) / 100;
            $fullViewsContent .= '
            <div class="view_block_img_block">
                <img class="view_block_img" src="'.$catalogView['img_full'].'" alt="часы">
            </div>

            <div class="view_block2">
                <p class="view_block_title">'.$catalogView['title'].'</p>
                <p class="view_block_discount_text">Скидка '.$catalogView['discount'].'%!</p>        
                <p class="view_block_discount">'.number_format($discount, 0, ',', ' ').' руб.</p>
                <p class="view_block_price_cross">'.number_format($catalogView['price'], 0, ',', ' ').' руб.</p>
                <a class="view_button" href="index.php?page=basket&action=add&product_id='.$catalogView['id'].'">Купить</a>
                <p class="view_block_description">'.$catalogView['description'].'</p>
            </div>
            ';
        } else
        {
            $fullViewsContent .= '
            <div class="view_block_img_block">
                <img class="view_block_img" src="'.$catalogView['img_full'].'" alt="часы">
            </div>

            <div class="view_block2">
                <p class="view_block_title">'.$catalogView['title'].'</p>
                <p class="view_block_price">'.number_format($catalogView['price'], 0, ',', ' ').' руб.</p>
                <a class="view_button" href="index.php?page=basket&action=add&product_id='.$catalogView['id'].'">Купить</a>
                <p class="view_block_description">'.$catalogView['description'].'</p>
            </div>
            ';
        }

        return $fullViewsContent;
    } else
    {
        header('Location: index.php');
        exit();
    }
    
}

function reviewView($connection, $user, $logged) // Вывод отзывов
{
    $reviewView = '';
    $result = mysqli_query($connection, "SELECT * FROM `reviews` ORDER BY `id` DESC");
    while( $reviews = mysqli_fetch_assoc($result) )
    {
        if( $logged == 'on' )
        {
            if( $reviews['u_id'] == $user['id'] or $user['admin'] == 1 )
            {
                $reviewView .= '
                <div class="comment_block">
                    <a class="comment_delete" href="index.php?page=reviews&action=delete&id='.$reviews['id'].'">Удалить</a>
                    <p class="comment_name">'.$reviews['name'].'</p>
                    <p class="comment_date">'.$reviews['date_pub'].'</p>
                    <p class="comment_text">'.$reviews['text'].'</p>
                </div>
                ';
            } else
            {
                $reviewView .= '
                <div class="comment_block">
                    <p class="comment_name">'.$reviews['name'].'</p>
                    <p class="comment_date">'.$reviews['date_pub'].'</p>
                    <p class="comment_text">'.$reviews['text'].'</p>
                </div>
                ';
            }
        } else
        {
            $reviewView .= '
            <div class="comment_block">
                <p class="comment_name">'.$reviews['name'].'</p>
                <p class="comment_date">'.$reviews['date_pub'].'</p>
                <p class="comment_text">'.$reviews['text'].'</p>
            </div>
            ';
        }
        
        
    }
    return $reviewView;
}

function reviewAdd($connection, $user, $text) // Добавление отзыва
{
    $datePub = date('d.m.Y').' в '.date('H:i');
    $text = trim(strip_tags($text));

    if( $text != '' )
    {
        $result = mysqli_query($connection, "INSERT INTO `reviews` (`u_id`, `name`, `text`, `date_pub`) VALUES ('".$user['id']."', '".$user['name']."', '$text', '$datePub');");
        return $result;
    }
}

function reviewDelete($connection, $user, $id) // Удаление отзыва
{
    $result = mysqli_query($connection, "SELECT * FROM `reviews` WHERE `id` = $id");
    $reviewsInp = mysqli_fetch_assoc($result);

    if( $reviewsInp['u_id'] == $user['id'] or $user['admin'] == 1 )
    {
        $res = mysqli_query($connection, "DELETE FROM `reviews` WHERE `id` = $id");
        return $res;
    }
}

function basketAddAction($connection, $logged, $u_id, $product_id)
{
    if( $logged == 'on' )
    {
        $res = mysqli_query($connection, "SELECT `user_id`, `product_id` FROM `basket` WHERE `user_id` = $u_id AND `product_id` = $product_id");

        if( mysqli_num_rows($res) > 0 )
        {
            $result = mysqli_query($connection, "UPDATE `basket` SET `quantity` = `quantity` + 1 WHERE `user_id` = $u_id AND `product_id` = $product_id");
        } else
        {
            $result = mysqli_query($connection, "INSERT INTO `basket` (`user_id`, `product_id`, `quantity`) VALUES ('$u_id', '$product_id', 1)");
        }

        return $result;
    }
}

function basketView($connection, $u_id)
{
    $catalogContent[0] = 0;
    $result = mysqli_query($connection, "SELECT * FROM `catalog`, `basket` WHERE catalog.id = basket.product_id AND basket.user_id = $u_id ORDER BY basket.id DESC");
    
    while( $catalog = mysqli_fetch_assoc($result) )
    {
        
        if( $catalog['discount'] > 0 )
        {
            $discount = ($catalog['price'] * (100 - $catalog['discount'])) / 100;
            $catalogContent[0] += ($discount * $catalog['quantity']);
            $catalogContent[1] .= '
            <div class="catalog">
                <input id="'.$catalog['id'].'" class="basket_col" type="number" name="col" value="'.$catalog['quantity'].'" min="1" max="999">
                <a class="catalog_link" href="index.php?page=view&id='.$catalog['product_id'].'">
                    <img class="catalog_img" src="'.$catalog['img_small'].'" alt="часы">
                    <p class="catalog_price_discount_text">Скидка '.$catalog['discount'].'%!</p>
                    <p class="catalog_price_discount">'.number_format($discount, 0, ',', ' ').' руб.</p>
                    <p class="catalog_price_cross">'.number_format($catalog['price'], 0, ',', ' ').' руб.</p>
                    <p class="catalog_title">'.$catalog['title'].'</p>
                </a>
                <a class="catalog_button2" href="index.php?page=order&id='.$catalog['product_id'].'&quantity='.$catalog['quantity'].'">Оформить</a>
                <a class="catalog_button3" href="index.php?page=basket&action=delete&id='.$catalog['product_id'].'">Удалить</a>
            </div>
            ';
        } else
        {
            $catalogContent[0] += ($catalog['price'] * $catalog['quantity']);
            $catalogContent[1] .= '
            <div class="catalog">
                <input id="'.$catalog['id'].'" class="basket_col" type="number" name="col" value="'.$catalog['quantity'].'" min="1" max="999">
                <a class="catalog_link" href="index.php?page=view&id='.$catalog['product_id'].'">
                    <img class="catalog_img" src="'.$catalog['img_small'].'" alt="часы">
                    <p class="catalog_price">'.number_format($catalog['price'], 0, ',', ' ').' руб.</p>
                    <p class="catalog_title">'.$catalog['title'].'</p>
                </a>
                <a class="catalog_button2" href="index.php?page=order&id='.$catalog['product_id'].'&quantity='.$catalog['quantity'].'">Оформить</a>
                <a class="catalog_button3" href="index.php?page=basket&action=delete&id='.$catalog['product_id'].'">Удалить</a>
            </div>
            ';
        }
        
    }

    return $catalogContent;
}

function basketDeleteAction($connection, $user, $deleteId)
{
    $result = mysqli_query($connection, "DELETE FROM `basket` WHERE `user_id` = $user AND `product_id` = $deleteId");
    return $result;
}

function basketCol($connection, $user)
{
    $result = mysqli_query($connection, "SELECT * FROM `basket` WHERE `user_id` = $user");
    $res = mysqli_num_rows($result);
    return $res;
}

function orderView($connection, $id, $quantity)
{
    $result = mysqli_query($connection, "SELECT * FROM `catalog` WHERE `id` = $id");
    $catalog = mysqli_fetch_assoc($result);

    if( $catalog['discount'] > 0 )
    {
        $catalog['price'] = ($catalog['price'] * (100 - $catalog['discount'])) / 100;
    }

    $catalog['price'] = $catalog['price'] * $quantity;

    return $catalog;
}

function orderAddAction($connection, $user, $id, $name, $email, $address, $phone, $quantity)
{
    $name = trim(strip_tags($name));
    $email = trim(strip_tags($email));
    $address = trim(strip_tags($address));
    $phone = trim(strip_tags($phone));
    $quantity = trim(strip_tags($quantity));

    if( $user != '' and $id != '' and $name != '' and $email != '' and $address != '' and $phone != '' and $quantity != '' )
    {
        $res = mysqli_query($connection, "SELECT * FROM `catalog` WHERE `id` = $id");
        $catalog = mysqli_fetch_assoc($res);

        if( $catalog['discount'] > 0 )
        {
            $catalog['price'] = ($catalog['price'] * (100 - $catalog['discount'])) / 100;
        }
        $catalog['price'] = $catalog['price'] * $quantity;

        $result = mysqli_query($connection, "INSERT INTO `orders` (`user_id`, `name`, `email`, `address`, `phone`, `product_id`, `product_name`, `quantity`, `price`, `status`) VALUES ('$user', '$name', '$email', '$address', '$phone', '$id', '".$catalog['title']."', '$quantity', '".$catalog['price']."', 0);");

        if($result)
        {
            $res = mysqli_query($connection, "DELETE FROM `basket` WHERE `user_id` = $user AND `product_id` = $id");
        }

        return $result;
    } else
    {
        return false;
    }
}

function lkOrdersView($connection, $user)
{
    $orderView[0] = 0;
    $result = mysqli_query($connection, "SELECT * FROM `orders` WHERE `user_id` = $user ORDER BY `id` DESC");

    $orderView[1] = mysqli_num_rows($result);

    while( $order = mysqli_fetch_assoc($result) )
    {
        $orderView[0] += $order['price'];
        if( $order['status'] == 0 )
        {
            $orderStatus = 'Новый';
        } else if( $order['status'] == 1 )
        {
            $orderStatus = 'Выполняется';
        } else
        {
            $orderStatus = 'Выполнен';
        }

        $orderView[2] .= '<tr><td>'.$order['name'].'</td><td>'.$order['email'].'</td><td>'.$order['address'].'</td><td>'.$order['phone'].'</td><td><a href="index.php?page=view&id='.$order['product_id'].'">'.$order['product_name'].'</a></td><td>'.$order['quantity'].'</td><td>'.number_format($order['price'], 0, ',', ' ').'</td><td>'.$orderStatus.'</td></tr>';
    }

    return $orderView;
}