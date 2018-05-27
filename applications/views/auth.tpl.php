<div class="auth_block">
    
    <div class="auth">
        <p class="auth_title">Авторизация</p>
        <form action="index.php?page=auth" method="POST">
            <p class="auth_inp_title">E-mail</p>
            <input class="inp inp_auth" type="email" name="email">

            <p class="auth_inp_title">Пароль</p>
            <input class="inp inp_auth" type="password" name="password"><br>

            <input class="button button_auth" type="submit" name="submit_login" value="Войти">
        </form>
    </div>

    <div class="auth">
        <p class="auth_title">Регистрация</p>
        <form action="index.php?page=auth" method="POST">
            <p class="auth_inp_title">Имя</p>
            <input class="inp inp_auth" type="text" name="name">

            <p class="auth_inp_title">E-mail</p>
            <input class="inp inp_auth" type="email" name="email">

            <p class="auth_inp_title">Пароль</p>
            <input class="inp inp_auth" type="password" name="password">

            <p class="auth_inp_title">Повтор пароля</p>
            <input class="inp inp_auth" type="password" name="to_password"><br>

            <input class="button button_auth" type="submit" name="submit_reg" value="Зарегистрироваться">
        </form>
    </div>

</div>