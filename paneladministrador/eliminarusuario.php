<?php
header( "refresh:0.1;url=listausuarios.php" );
include_once ("coneccionbd.php");
// Iniciar sesion
session_start();
$todelete= mysqli_real_escape_string($con,$_GET["id"]);

$result=mysqli_query($con,"DELETE FROM usuario WHERE admId='$todelete'");

?>
