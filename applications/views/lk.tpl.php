<div class="lk_block">
    <p class="lk_welcome"><?=$user['name']?>, добро пожаловать в личный кабинет!</p>

    <form action="index.php?page=lk" method="POST">
        <p class="auth_inp_title">Имя</p>
        <input class="inp inp_auth" type="text" name="name" value="<?=$user['name']?>" autocomplete="off">

        <p class="auth_inp_title">E-mail</p>
        <input class="inp inp_auth" type="email" name="email" value="<?=$user['email']?>" autocomplete="off">

        <input class="button button_auth" type="submit" name="submit_change" value="Изменить">
    </form>
    
    <div class="lk_orders">
        <?php
        if( $lkOrdersView[1] > 0 )
        {
        ?>
        <hr>
        <p class="lk_orders_title">Ваши заказы</p>
        <p class="lk_orders_sum">Общая сумма: <span class="lk_orders_sum2"><?=number_format($lkOrdersView[0], 0, ',', ' ')?> руб.</span></p>

        <table class="" cellspacing="0">
		    <thead><tr><th scope="col">Имя</th><th scope="col">E-mail</th><th scope="col">Адрес</th><th scope="col">Телефон</th><th scope="col">Товар</th><th scope="col">Количество</th><th scope="col">Сумма</th><th scope="col">Статус</th></tr></thead>
            <?=$lkOrdersView[2]?>
        </table>
        <?php
        }
        ?>
    </div>

</div>

