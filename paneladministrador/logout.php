<?php
session_start();
// Elimina todas las variables de la sesión
session_unset();
// Destruye la sesión
session_destroy();
// Redirige al login
header("Location: /paneladministrador/login.php");
exit();
?>
