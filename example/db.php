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
        $massQuery = '';

        foreach ($data['transmitter'] as $typeId => $typeElement) {
            $massQuery = 'INSERT INTO transmitter (name, lat, lng, region, transmitter_id) VALUES ("' . $typeElement['name'] . '", ' . $typeElement['lat'] . ', ' . $typeElement['lng'] . ', "' . $typeElement['region'] . '", ' . $typeId . ');';
            $this->connection->exec($massQuery);
            foreach ($typeElement['stations']['transmitters'] as $radio) {
                $massQuery = 'INSERT INTO transmitter_radio (name, frequency, transmitter_id, url, radio_id) VALUES ("' . $radio['station'] . '", ' . (float) $radio['frequency_or_channel'] . ', ' . $typeId . ', "' . $radio['station_url'] . '", ' . str_replace('pl/stations/radio-stations/station/', '', trim($radio['station_url'], '/')) . ');';
                $this->connection->exec($massQuery);
            }
        }
        foreach ($data['city'] as $typeId => $typeElement) {
            $massQuery = 'INSERT INTO city (name, lat, lng, region, city_id) VALUES ("' . $typeElement['name'] . '", ' . $typeElement['lat'] . ', ' . $typeElement['lng'] . ', "' . $typeElement['region'] . '", ' . $typeId . ');';
            $this->connection->exec($massQuery);
            foreach ($typeElement['stations']['transmitters'] as $radio) {
                $massQuery = 'INSERT INTO city_radio (name, frequency, city_id, url, radio_id) VALUES ("' . $radio['station'] . '", ' . (float) $radio['frequency_or_channel'] . ', ' . $typeId . ', "' . $radio['station_url'] . '", ' . str_replace('pl/stations/radio-stations/station/', '', trim($radio['station_url'], '/')) . ');';
                $this->connection->exec($massQuery);
            }
        }
    }

    /**
     * return closest transmitters from database to given lat and lng coordinates 
     * 
     * 
     * @param float $lat
     * @param float $lng
     * @return  json string
     */
    public function closestCities($lat, $lng) {
        $query = 'SELECT *, acos(sin(radians(lat))*sin(radians(:lat))+cos(RADIANS(lat))*cos(radians(:lat))*cos(RADIANS(lng-:lng)))*6371 as distance 
    FROM transmitter 
    WHERE acos(sin(radians(lat))*sin(radians(:lat))+cos(RADIANS(lat))*cos(radians(:lat))*cos(RADIANS(lng-:lng)))*6371 < :distance
    ORDER BY distance asc LIMIT 20';
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':lat', $lat);
        $stmt->bindValue(':lng', $lng);
        $distance = 100;
        $stmt->bindValue(':distance', $distance);
        $stmt->execute();
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

}
