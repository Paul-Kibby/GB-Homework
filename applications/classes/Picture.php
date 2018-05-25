<?php

class Picture {

    private $connection;
    public $catalog;


    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getCatalog()
    {
        $result = mysqli_query($this->connection, "SELECT * FROM `picture`");
        $catalog = '';

        if( mysqli_num_rows($result) > 0 )
        {
            while( $cat = mysqli_fetch_assoc($result) )
            {
                $catalog .= '<div class="img-block"><a class="ib-delete" href="/?delete='.$cat['id'].'">Удалить</a><a href="view.php?id='.$cat['id'].'"><img src="'.$cat['url_small'].'" alt="'.$cat['name'].'"></a></div>';
            }
        } else
        {
            $catalog = '<div class="catalog-null">Изображения не найдены</div>';
        }

        $this->catalog = $catalog;
    }

    public function getSingle($id)
    {
        $result = mysqli_query($this->connection, "SELECT `name`, `url` FROM `picture` WHERE `id` = {$id}");
        if( mysqli_num_rows($result) > 0 )
        {
            $img = mysqli_fetch_assoc($result);
            $return = '<img src="'.$img['url'].'" alt="'.$img['name'].'">';
        } else
        {
            $return = '<div class="view-error">Изображение не найдено</div>';
        }

        return $return;
    }

    public function upload($file)
    {
        $types = array('image/jpeg', 'image/png', 'image/gif');
        if( in_array($file['type'], $types) )
        {
            $pathInfo = pathinfo($file['name']);
            $newName = date('d.m.Y-H.i.s') . '.' . $pathInfo['extension'];

            $pathFull = 'public/img/full/'.$newName;
            $pathSmall = 'public/img/small/'.$newName;

            $width = 300;
            $height = 300;

            move_uploaded_file($file['tmp_name'], $pathFull);
            $this->createThumbnail($pathFull, $pathSmall, $width, $height);

            $result = mysqli_query($this->connection, "INSERT INTO `picture` (`name`, `url`, `url_small`) VALUES ('{$pathInfo['extension']}', '$pathFull', '$pathSmall')");
            return $result;
        }
    }

    public function delete($id)
    {
        $res = mysqli_query($this->connection, "SELECT * FROM `picture` WHERE `id` = {$id}");

        if( mysqli_num_rows($res) > 0 )
        {
            $img = mysqli_fetch_assoc($res);
            $result = mysqli_query($this->connection, "DELETE FROM `picture` WHERE `id` = {$id}");

            unlink($img['url']);
            unlink($img['url_small']);

            return $result;
        } else
        {
            return false;
        }
    }

    public function createThumbnail($path, $save, $width, $height) {
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

}