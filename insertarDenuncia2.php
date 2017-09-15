<?php
$servername = "127.0.0.1:51527";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

$misDatos = file_get_contents('php://input');
$miDenuncia = json_decode($misDatos,true);

$latitud = $miDenuncia["latitud"];
$longitud = $miDenuncia["longitud"];
$tipo = $miDenuncia["tipo"];
$descripcion = $miDenuncia["descripcion"];
$fecha = $miDenuncia["fecha"];


try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "INSERT INTO misdenuncias SET latitud = :lat, longitud=:lon, tipo=:tip, descripcion=:des, fecha=:fec";
	$STH = $DBH->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);

	$params = array(
	":lat" => $latitud,
	":lon" =>  $longitud,
	":tip" => $tipo,
	":des" => $descripcion, 
	":fec" => $fecha
	);
	
	$STH->execute($params);
	
	echo "funciono";
	
} catch (PDOException $e) {
	echo "error";
}

$DBH = null;

?>
