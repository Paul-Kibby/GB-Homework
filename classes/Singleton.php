<?php

trait ST {
    public static function getInstance()
    {
        if( is_null(self::$_instance) )
        {
            return new self();
        }
        return self::$_instance;
    }
}

class Singleton {

    private static $_instance;

    use ST;

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}