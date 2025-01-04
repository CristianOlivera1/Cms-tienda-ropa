<?php
header( "refresh:0.1; url=gestionar-color.php" );
include_once ("../../coneccionbd.php");
// Iniciar sesion
session_start();
$todelete= mysqli_real_escape_string($con,$_GET["id"]);

$result=mysqli_query($con,"DELETE FROM color WHERE colId='$todelete'");

?>