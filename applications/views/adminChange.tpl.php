<div class="admin_panel">
    <p class="admin_panel_title">Редактировать товар</p>

    <div class="admin_panel_panel">

        <form action="index.php?page=admin&action=change" method="POST" enctype="multipart/form-data">
            <p class="admin_inp_title">Наименование товара</p>
            <input class="inp inp_admin" type="text" name="title" value="<?=$changeCat['title']?>" required>

            <p class="admin_inp_title">Описание</p>
            <textarea class="inp_text inp_text_admin" name="description" required><?=$changeCat['description']?></textarea>

            <p class="admin_inp_title">Цена</p>
            <input class="inp inp_admin" type="text" name="price" value="<?=$changeCat['price']?>" required>

            <p class="admin_inp_title">Скидка (%)</p>
            <input class="inp inp_admin" type="text" name="discount" value="<?=$changeCat['discount']?>" required>

            <input class="inp_file" type="file" name="upload_img" accept="image/jpeg, image/png, image/gif">

            <input type="hidden" name="id" value="<?=$changeCat['id']?>">
            <input type="hidden" name="img_old_full" value="<?=$changeCat['img_full']?>">
            <input type="hidden" name="img_old_small" value="<?=$changeCat['img_small']?>">

            <input class="button button_admin" type="submit" name="submit_change" value="Изменить">
        </form>
    </div> 
</div>