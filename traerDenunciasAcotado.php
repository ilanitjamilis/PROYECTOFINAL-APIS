<?php
$servername = "127.0.0.1:51533";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

$misDatos = file_get_contents('php://input');
$misParametros = json_decode($misDatos,true);

$minLatP = $misParametros["minLat"];
$maxLatP = $misParametros["maxLat"];
$minLongP = $misParametros["minLong"];
$maxLongP = $misParametros["maxLong"];

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "SELECT latitud, longitud, tipo, descripcion FROM denuncias WHERE latitud Between :minLat And :maxLat And longitud Between :minLon And :maxLon";
	$STH = $DBH->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	
	$params = array(
	":minLat" => $minLatP,
	":maxLat" =>  $maxLatP,
	":minLon" => $minLongP,
	":maxLon" => $maxLongP
	);
	
	$STH->execute(params);
	
	$resultados = $STH->fetchAll();
	echo json_encode($resultados);
	
} catch (PDOException $e) {
	echo "error";
}

$DBH = null;

?>
