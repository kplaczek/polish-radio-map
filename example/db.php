<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author krzysiek
 */
class db {

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $base = 'rmp';
    private $connection = null;

    const FETCH_TYPE = PDO::FETCH_OBJ;

    public function __construct() {
        if (is_null($this->connection)) {
            $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->base . ';encoding=utf8', $this->user, $this->pass);
            $this->connection->exec("set names utf8");
        }

        return $this->connection;
    }

    /**
     * 
     * @return array
     */
    public function getRegions() {
        $stmt = $this->connection->query('SELECT id,code,name FROM region');
        return $stmt->fetchAll(self::FETCH_TYPE);
    }

}
