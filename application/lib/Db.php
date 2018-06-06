<?php

namespace application\lib;

use PDO;

class Db
{
    protected $db;

    public function __construct()
    {
        $config = require 'application/config/db.php';
        $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'].';', $config['username'], $config['password']);
    }

    public function select($sql, $params = [])
    {
        $query = $this->db->prepare($sql);

        if( !empty($params) )
        {
            foreach( $params as $key => $val )
            {
                $query->bindValue(':'.$key, $val);
            }
        }

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}