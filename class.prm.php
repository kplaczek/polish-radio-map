<?php

class prm {

    const CITY = "city";
    const TRANSMITTER = "transmitter";

    public $data = array(self::CITY => array(), self::TRANSMITTER => array());
    private $types = ['cities', 'transmitters'];
    private $db = null;

    public function __construct() {

        $this->db = new db();
        $rawRegions = $this->getRegions();
        $parsedData = $this->parseCities($rawRegions);
        $superDetailData = $this->getDetailedInfo($parsedData);
        
        //save to database for later 
        $this->db->saveData($superDetailData);
    }

    private function getRegions() {
        $regionsRaw = [];
        foreach ($this->types as $type) {
            foreach ($this->db->getRegions() as $region) {
                $regionsRaw[$type][$region->code] = $data = getdata::c('http://nadaje.com/pl/transmitters/polish-radio-map/?request_marker=marker&region=' . $region->code . '&show_what=' . $type);
            }
        }
        return $regionsRaw;
    }

    private function parseCities($rawRegions) {
        $data = array(self::CITY => array(), self::TRANSMITTER => array());
        foreach ($rawRegions as $type => $regions) {
            foreach ($regions as $regionCode => $regionRaw) {
                preg_match_all('#data-type="(transmitter|city)" data-lat="(\d*\.\d*)" data-lng="(\d*\.\d*)" data-object-url="/pl/transmitters/(cities/city|transmitter/location)/(\d*?)/" data-marker-label="(.*?)">#ims', str_replace("\n", '', preg_replace('#\s{2,}#ism', ' ', $regionRaw)), $found);
                foreach ($found[1] as $key => $place) {
                    $data[$place][$found[5][$key]] = array(
                        'name' => $found[6][$key],
                        'lat' => $found[2][$key],
                        'lng' => $found[3][$key],
                        'cityId' => $found[5][$key],
                        'region' => $regionCode
                    );
                }
            }
        }
        return $data;
    }

    private function getDetailedInfo($parsedData) {
        foreach ($parsedData as $type => &$typeArray) {
            foreach ($typeArray as $id => &$info) {
                $info['stations'] = $this->getDetails($type, $id);
            }
        }
        return $parsedData;
    }

    private function getDetails($type, $objectId) {

        switch ($type) {
            case self::TRANSMITTER:
                $url = 'http://nadaje.com/pl/transmitters/transmitter/location/' . $objectId;
                break;
            case self::CITY:
                $url = 'http://nadaje.com/pl/transmitters/cities/city/' . $objectId;
                break;
        }

        $headers = array('X-Requested-With: XMLHttpRequest');
        $data = getdata::c($url, null, true, null, $headers);
        return json_decode($data, 1);
    }

}
