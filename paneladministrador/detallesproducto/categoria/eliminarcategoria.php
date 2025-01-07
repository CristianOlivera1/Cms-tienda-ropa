<?php
header("refresh:0.1 ;url=gestionar-categoria.php");
include_once ("../../coneccionbd.php");
// Iniciar sesion
session_start();
$todelete= mysqli_real_escape_string($con,$_GET["id"]);

$result=mysqli_query($con,"DELETE FROM categoria WHERE catId='$todelete'");

?>
