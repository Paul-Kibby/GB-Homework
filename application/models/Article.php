<?php
//
//class Article extends PageController
//{
//
//    public function getAll()
//    {
//        $res = $this->pdo->prepare("SELECT * FROM `article`");
//        $res->execute();
//        $result = $res->fetchAll(PDO::FETCH_ASSOC);
//
//        $article = '';
//        foreach($result as $art)
//        {
//            $article .= '
//            <div class="home-article">
//                <a class="home-article-link" href="/?page=article&id='.$art['id'].'">
//                    <p class="home-article-title">'.$art['title'].'</p>
//                    <p class="home-article-date">'.$art['date'].'</p>
//                </a>
//            </div>
//            ';
//        }
//
//        return $article;
//    }
//
//    public function get($id)
//    {
//        $res = $this->pdo->prepare("SELECT * FROM `article` WHERE `id` = $id");
//        $res->execute();
//        $art = $res->fetch(PDO::FETCH_ASSOC);
//
//        return $art;
//    }
//
//}