<?php
$servername = "127.0.0.1:51533";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "SELECT latitud, longitud, tipo, descripcion FROM denuncias";
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
