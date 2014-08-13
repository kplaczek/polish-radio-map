<?php

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

    public function saveData($data) {
        $massQuery = [];

        foreach ($data['transmitter'] as $typeId => $typeElement) {
            $massQuery[] = 'INSERT INTO transmitter (id, name, lat, lng, region, transmitter_id) VALUES (NULL, "' . $typeElement['name'] . '", ' . $typeElement['lat'] . ', ' . $typeElement['lng'] . ', "' . $typeElement['region'] . '", ' . $typeId . ')';
        }
        foreach ($data['city'] as $typeId => $typeElement) {
            $massQuery[] = 'INSERT INTO transmitter (id, name, lat, lng, region, transmitter_id) VALUES (NULL, "' . $typeElement['name'] . '", ' . $typeElement['lat'] . ', ' . $typeElement['lng'] . ', "' . $typeElement['region'] . '", ' . $typeId . ')';
        }
        echo $this->connection->exec(implode(';', $massQuery));
        
    }

}
