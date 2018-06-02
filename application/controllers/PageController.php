<?php

require 'application/models/mainModel.php';

class PageController extends MainController
{

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->user = checkLogin($this->pdo);
        session_start();
    }

    public function home()
    {
        $article = getAllArticles($this->pdo);
        $vars = array('articles' => $article);
        $this->content = $this->Template('application/views/home.tpl.php', $vars);
    }

    public function article()
    {
        $art = getArticle($this->pdo, $this->user, $_GET['id']);
        $vars = array('artTitle' => $art['title'], 'artDate' => $art['date'], 'artText' => $art['text']);
        $this->content = $this->Template('application/views/article.tpl.php', $vars);
    }

    public function login()
    {
        if( $this->user )
        {
            header("Location: /?page=profile");
            exit;
        }

        if( isset($_GET['act']) )
        {
            if( $_GET['act'] == 'register' )
            {
                if( isset($_POST['do_reg']) )
                {
                    registerAction($this->pdo, $_POST['name'], $_POST['email'], $_POST['password']);
                }

            }

            if( $_GET['act'] == 'login' )
            {
                if( isset($_POST['do_log']) )
                {
                    loginAction($this->pdo, $_POST['email'], $_POST['password']);
                }
            }
        }

        $vars = array('test' => '123');
        $this->content = $this->Template('application/views/login.tpl.php', $vars);
    }

    public function profile()
    {
        if( !$this->user )
        {
            header("Location: /?page=login");
            exit;
        }

        if( isset($_GET['exit']) )
        {
            exitAction();
        }
        $recently = recently($this->pdo);
        $vars = array('user' => $this->user, 'recently' => $recently);
        $this->content = $this->Template('application/views/profile.tpl.php', $vars);
    }

}

require 'application/models/Article.php';
require 'application/models/User.php';