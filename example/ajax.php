<?php
/**
 * simple ajax file, returns closest transmitters as json string.
 */

include_once 'db.php';
$db = new db();
$transmitters =  $db->closestTransmitters($_POST['lat'], $_POST['lng']);

$stations = $db->closestRadioStations($_POST['lat'], $_POST['lng']);

echo json_encode(array($transmitters, $stations));

