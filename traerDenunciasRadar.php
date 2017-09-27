<?php
$servername = "127.0.0.1:51527";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

$misDatos = file_get_contents('php://input');
$misParametros = json_decode($misDatos,true);

$latRecibida = $misParametros["miLat"];
$lngRecibida = $misParametros["miLng"];

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "SELECT latitud, longitud, tipo, descripcion, fecha FROM misdenuncias WHERE ACOS( SIN( RADIANS( `latitud` ) ) * 
	SIN( RADIANS( :lat ) ) + COS( RADIANS( `latitud` ) )* COS( RADIANS( :lat )) * COS( RADIANS( `longitud` ) - 
	RADIANS( :lng )) ) * 6380 < 1";
	
	$STH = $DBH->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	
	$params = array(
	":lat" => $latRecibida,
	":lng" =>  $lngRecibida,
	);
	
	$STH->execute(params);
	
	$resultados = $STH->fetchAll();
	echo json_encode($resultados);
	
} catch (PDOException $e) {
	echo "error";
}

$DBH = null;

?>