<?php
$servername = "127.0.0.1:51527";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "localdb";

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "SELECT latitud, longitud, Tipo, Descripcion FROM denuncias";
	$STH = $DBH->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	$STH->execute();
	
	$resultados = $STH->fetchAll();
	echo json_encode($resultados);
} catch (PDOException $e) {
	echo "error";
}

$conn = null;

?>