<?php
$servername = "127.0.0.1:51527";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

$misDatos = $_POST["body"];
$miDenuncia = json_decode($misDatos);
$latitud = $miDenuncia["latitud"];
$longitud = $miDenuncia["longitud"]
$tipo = $miDenuncia["tipo"];
$descripcion = $miDenuncia["descripcion"];

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query="INSERT INTO denuncias SET latitud = :lat, longitud=:long, Tipo=:tip, Descripcion=:descr";
	$STH = $DBH->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);

	$params = array(
	":lat" => $latitud,
	":long" =>  $longitud,
	":tip" => $tipo,
	":descr" => $descripcion
	);
	
	$STH->execute($params);
	
	echo "funciono";
	
} catch (PDOException $e) {
	echo "error" . $e->getMessage( );
}

$DBH = null;

?>
