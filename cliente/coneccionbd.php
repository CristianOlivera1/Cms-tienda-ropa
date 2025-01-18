<?php
$nombre_servidor = "localhost";
$usuario = "root";
$contrasena = "";
$nombre_bd = "tiendacms";

$con = new mysqli($nombre_servidor, $usuario, $contrasena, $nombre_bd);

// Verificar la conexión
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}
?>
