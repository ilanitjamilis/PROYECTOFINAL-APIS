<?php
$servername = "127.0.0.1:51527";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

$misDatos = file_get_contents('php://input');
$misParametros = json_decode($misDatos,true);

$latRecibida = $misParametros["miLat"];
$lngRecibida = $misParametros["miLng"];

$R = 6371;
$rad = 2;

$maxLat = $latRecibida + rad2deg($rad/$R);
$minLat = $latRecibida - rad2deg($rad/$R);
$maxLon = $lngRecibida + rad2deg(asin($rad/$R) / cos(deg2rad($latRecibida)));
$minLon = $lngRecibida - rad2deg(asin($rad/$R) / cos(deg2rad($latRecibida)));

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT latitud, longitud, tipo, descripcion, fecha
        FROM misdenuncias 
        WHERE latitud Between :minLat And :maxLat
          And longitud Between :minLon And :maxLon";
	
	$query = "SELECT latitud, longitud, tipo, descripcion, fecha, 
	(
        6371 *
        acos(
            cos( radians( :lat ) ) *
            cos( radians( `latitud` ) ) *
            cos(
                radians( `longitud` ) - radians( :lng )
            ) +
            sin(radians(:lat)) *
            sin(radians(`latitud`))
        )
    ) AS distancia
	FROM misdenuncias 
	HAVING
    distancia < 25 ";
	
	$STH = $DBH->prepare($sql);
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	
	//git add --all && git commit -m "subo"
	
	$params = array(
	":minLat" => $minLat,
	":maxLat" =>  $maxLat, 
	":minLon" => $minLon,
	":maxLon" =>  $maxLon
	);
	
	$STH->execute(params);
	
	$resultados = $STH->fetchAll();
	echo json_encode($resultados);
	
} catch (PDOException $e) {
	echo "error";
}

$DBH = null;

?>