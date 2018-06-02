<?php
//
//class User extends PageController
//{
//
//    public function __construct($pdo)
//    {
//        $this->pdo = $pdo;
//        $this->check();
//    }
//
//    public function check()
//    {
//        $sql = "SELECT * FROM `users` WHERE `id` = '".$_COOKIE['u_id']."' AND `password` = '".$_COOKIE['u_pass']."'";
//        $res = $this->pdo->prepare($sql);
//        $res->execute();
//        $user = $res->fetch(PDO::FETCH_ASSOC);
//
//        if( $user )
//        {
//            $this->logged = true;
//            return $user;
//        } else
//        {
//            setcookie('u_id', '', time()- (3600 * 24 * 30) );
//            setcookie('u_pass', '', time() - (3600 * 24 * 30) );
//        }
//    }
//
//    public function register($name, $email, $password)
//    {
//
//        $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (:name, :email, :password)";
//        $reg = $this->pdo->prepare($sql);
//        $reg->bindParam(":name", $name);
//        $reg->bindParam(":email", $email);
//        $reg->bindParam(":password", $password);
//        $reg->execute();
//
//
//        $sql2 = "SELECT * FROM `users` WHERE `email` = '$email'";
//        $reg2 = $this->pdo->prepare($sql2);
//        $reg2->execute();
//        $user = $reg2->fetch(PDO::FETCH_ASSOC);
//
//        if( $user )
//        {
//            setcookie('u_id', $user['email'], time() + (3600 * 24 * 30) );
//            setcookie('u_pass', $user['password'], time() + (3600 * 24 * 30) );
//
//            header("Location: /?page=profile");
//            exit;
//        }
//
//    }
//}