<?php
/**
 * simple ajax file, returns closest transmitters as json string.
 */

include_once 'db.php';
$db = new db();
echo $db->closestCities($_POST['lat'], $_POST['lng']);
