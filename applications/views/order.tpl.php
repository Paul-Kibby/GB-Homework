<div class="view_block">
    <div></div>
    <div class="order_block">
        <p class="order_title">Оформление заказа</p>
        <p class="order_title_name"><?=$orderView['title']?></p>
        <form action="index.php?page=order&id=<?=$_GET['id']?>&quantity=<?=$_GET['quantity']?>" method="POST">
            <p class="inp_title_order">Имя</p>
            <input class="inp inp_order" type="text" name="name" value="<?=$user['name']?>" autocomplete="off" required>

            <p class="inp_title_order">E-mail</p>
            <input class="inp inp_order" type="email" name="email" value="<?=$user['email']?>" autocomplete="off" required>

            <p class="inp_title_order">Адрес</p>
            <input class="inp inp_order" type="text" name="address" autocomplete="off" required>

            <p class="inp_title_order">Телефон</p>
            <input class="inp inp_order" type="text" name="phone" value="+7" autocomplete="off" required>

            <input type="hidden" name="quantity" value="<?=$_GET['quantity']?>">

            <hr style="width: 500px;">

            <p class="order_info">Количество: <span class="order_info_col"><?=$_GET['quantity']?> шт.</span></p>
            <p class="order_info">Цена: <span class="order_info_price"><?=number_format($orderView['price'], 0, ',', ' ')?> руб.</span></p>

            <input class="button button_order" type="submit" name="submit_order" value="Оформить заказ">
        </form>
    </div>
</div>