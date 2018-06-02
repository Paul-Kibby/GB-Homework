<?php

class MainController
{
    protected $pdo;
    protected $title = 'Название сайта';
    protected $content;
    protected $user = false;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function Request($page = 'home')
    {
        $this->$page();
        $this->Render();
    }

    protected function Template($fileName, $vars)
    {
        foreach( $vars as $k => $v )
        {
            $$k = $v;
        }

        ob_start();
        require $fileName;
        return ob_get_clean();
    }

    protected function Render()
    {
        $vars = array('title' => $this->title, 'content' => $this->content, 'user' => $this->user);
        $page = $this->Template('application/views/main.tpl.php', $vars);
        echo $page;
    }

    public function __call($name, $arguments)
    {
        exit('Метод не найден!');
    }
}