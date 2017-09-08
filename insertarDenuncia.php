<?php
$servername = "127.0.0.1:51527";
$username = "azure";
$password = "6#vWHD_$";
$dbname = "db";

//$misDatos = $_POST["body"];
$misDatos = file_get_contents('php://input');
$miDenuncia = json_decode($misDatos);

$latitud = $miDenuncia["latitud"];
$longitud = $miDenuncia["longitud"];
$tipo = $miDenuncia["tipo"];
$descripcion = $miDenuncia["descripcion"];

echo $tipo;
exit("asad");

try {
	$DBH = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query="INSERT INTO denuncias SET latitud = :lat, longitud=:lon, tipo=:tip, descripcion=:des";
	$STH = $DBH->prepare($query);
	$STH->setFetchMode(PDO::FETCH_ASSOC);

	$params = array(
	":lat" => $latitud,
	":lon" =>  $longitud,
	":tip" => $tipo,
	":des" => $descripcion
	);
	
	$STH->execute($params);
	
	echo "funciono";
	
} catch (PDOException $e) {
	echo "error".$e->getMessage();
}

$DBH = null;

?>
