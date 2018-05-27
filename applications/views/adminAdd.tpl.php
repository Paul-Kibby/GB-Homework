<div class="admin_panel">
    <p class="admin_panel_title">Добавить товар</p>

    <div class="admin_panel_panel">
        <form action="index.php?page=admin&action=add" method="POST" enctype="multipart/form-data">
            <p class="admin_inp_title">Наименование товара</p>
            <input class="inp inp_admin" type="text" name="title" autocomplete="off" required>

            <p class="admin_inp_title">Описание</p>
            <textarea class="inp_text inp_text_admin" name="description" autocomplete="off" required></textarea>

            <p class="admin_inp_title">Цена</p>
            <input class="inp inp_admin" type="text" name="price" autocomplete="off" required>

            <p class="admin_inp_title">Скидка (%)</p>
            <input class="inp inp_admin" type="text" name="discount" autocomplete="off" value="0" required>

            <input class="inp_file" type="file" name="upload_img" accept="image/jpeg, image/png, image/gif" required>

            <input class="button button_admin" type="submit" name="submit_add" value="Добавить">
        </form>
    </div> 
</div>