<div class="profile">
    <p class="profile-title">Добро пожаловать в личный кабинет, <?=$user['name']?>!</p>
    <a class="profile-exit" href="/?page=profile&exit=1">Выход</a>

    <div class="profile-recently">
        <p class="pr-title">Вы недавно смотрели:</p>
        <div class="pr-list">
            <?=$recently?>
        </div>
    </div>
</div>