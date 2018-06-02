<?php

function checkLogin($pdo)
{
    $sql = "SELECT * FROM `users` WHERE `id` = ".$_COOKIE['u_id']." AND `password` = '".$_COOKIE['u_pass']."'";
    $res = $pdo->prepare($sql);
    $res->execute();
    $user = $res->fetch(PDO::FETCH_ASSOC);

    if( $user )
    {
        return $user;
    } else
    {
        setcookie('u_id', '', time() - (3600 * 24 * 30) );
        setcookie('u_pass', '', time() - (3600 * 24 * 30) );
    }
}

function registerAction($pdo, $name, $email, $password)
{

    $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (:name, :email, :password)";
    $reg = $pdo->prepare($sql);
    $reg->bindParam(":name", $name);
    $reg->bindParam(":email", $email);
    $reg->bindParam(":password", $password);
    $reg->execute();


    $sql2 = "SELECT * FROM `users` WHERE `email` = '$email'";
    $reg2 = $pdo->prepare($sql2);
    $reg2->execute();
    $user = $reg2->fetch(PDO::FETCH_ASSOC);

    if( $user )
    {
        setcookie('u_id', $user['id'], time() + (3600 * 24 * 30) );
        setcookie('u_pass', $user['password'], time() + (3600 * 24 * 30) );

        header("Location: /?page=profile");
        exit;
    }

}

function loginAction($pdo, $email, $password)
{
    $res = $pdo->prepare("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
    $res->execute();
    $user = $res->fetch(PDO::FETCH_ASSOC);

    if( $user )
    {
        setcookie('u_id', $user['id'], time() + (3600 * 24 * 30) );
        setcookie('u_pass', $user['password'], time() + (3600 * 24 * 30) );

        header("Location: /?page=profile");
        exit;
    }
}

function exitAction()
{
    setcookie('u_id', '', time() - (3600 * 24 * 30) );
    setcookie('u_pass', '', time() - (3600 * 24 * 30) );
    unset($_SESSION['po']);
    header("Location: /?page=login");
    exit;
}


// Articles

function getAllArticles($pdo)
{
    $res = $pdo->prepare("SELECT * FROM `article`");
    $res->execute();
    $result = $res->fetchAll(PDO::FETCH_ASSOC);

    $article = '';
    foreach($result as $art)
    {
        $article .= '
            <div class="home-article">
                <a class="home-article-link" href="/?page=article&id='.$art['id'].'">
                    <p class="home-article-title">'.$art['title'].'</p>
                    <p class="home-article-date">'.$art['date'].'</p>
                </a>
            </div>
            ';
    }

    return $article;
}

function getArticle($pdo, $user, $id)
{
    $res = $pdo->prepare("SELECT * FROM `article` WHERE `id` = $id");
    $res->execute();
    $art = $res->fetch(PDO::FETCH_ASSOC);

    if( $user )
    {
        if (!isset($_SESSION['po']))
        {
            $_SESSION['po']=array();
        }

        if( !in_array($art['id'], $_SESSION['po']) ) {
            $_SESSION['po'][] = $art['id'];
        }

        if (count($_SESSION['po'])>5)
        {
            array_shift($_SESSION['po']);
        }
    }


    return $art;
}

function recently($pdo)
{
    if( isset($_SESSION['po']) )
    {
        $i = 0;
        $sql = '';
        foreach( $_SESSION['po'] as $key => $value )
        {
            if( $i == 0 )
            {
                $sql = "SELECT * FROM `article` WHERE `id` IN ($value";
            } else
            {
                $sql .= ", $value";
            }
            $i++;
        }
        $sql .= ")";

        $res = $pdo->prepare($sql);
        $res->execute();
        $result = $res->fetchAll(PDO::FETCH_ASSOC);

        $recently = '';
        foreach($result as $art)
        {
            $recently .= '
            <div class="recently-article">
                <a class="recently-article-link" href="/?page=article&id='.$art['id'].'">
                    <p class="recently-article-title">'.$art['title'].'</p>
                </a>
            </div>
            ';
        }

        return $recently;
    } else
    {
        return '<p class="recently-none">История пуста</p>';
    }


}