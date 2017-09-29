<?php
$servername = "127.0.0.1:51527";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

$misDatos = file_get_contents('php://input');
$misParametros = json_decode($misDatos,true);

$latRecibida = $misParametros["miLat"];
$lngRecibida = $misParametros["miLng"];


function getBoundaries($lat, $lng, $distance, $earthRadius)
{
    $return = array();
     
    // Los angulos para cada dirección
    $cardinalCoords = array('north' => '0',
                            'south' => '180',
                            'east' => '90',
                            'west' => '270');
    $rLat = deg2rad($lat);
    $rLng = deg2rad($lng);
    $rAngDist = $distance/$earthRadius;
    foreach ($cardinalCoords as $name => $angle)
    {
        $rAngle = deg2rad($angle);
        $rLatB = asin(sin($rLat) * cos($rAngDist) + cos($rLat) * sin($rAngDist) * cos($rAngle));
        $rLonB = $rLng + atan2(sin($rAngle) * sin($rAngDist) * cos($rLat), cos($rAngDist) - sin($rLat) * sin($rLatB));
         $return[$name] = array('lat' => (float) rad2deg($rLatB), 
                                'lng' => (float) rad2deg($rLonB));
    }
    return array('min_lat'  => $return['south']['lat'],
                 'max_lat' => $return['north']['lat'],
                 'min_lng' => $return['west']['lng'],
                 'max_lng' => $return['east']['lng']);
}

$distance = 1; // Sitios que se encuentren en un radio de 1KM
$box = getBoundaries($latRecibida, $lngRecibida, 1, 6371);

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$query = "SELECT latitud, longitud, tipo, descripcion, fecha, 
	(6371 * ACOS( 
                                            SIN(RADIANS(latitud)) 
                                            * SIN(RADIANS(".$latRecibida.")) 
                                            + COS(RADIANS(longitud - ".$lngRecibida.")) 
                                            * COS(RADIANS(latitud)) 
                                            * COS(RADIANS(".$latRecibida."))
                                            )
                               ) AS distance
							   
        FROM misdenuncias 
		WHERE (latitud BETWEEN ".$box['min_lat']. " AND " . $box['max_lat'] . ")
                     AND (longitud BETWEEN " . $box['min_lng']. " AND " . $box['max_lng']. ")
                     HAVING distance  < " . $distance . ";

	
	$STH = $DBH->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);

	
	$STH->execute();
	
	$resultados = $STH->fetchAll();
	echo json_encode($resultados);
	
} catch (PDOException $e) {
	echo "error";
}

$DBH = null;

?>