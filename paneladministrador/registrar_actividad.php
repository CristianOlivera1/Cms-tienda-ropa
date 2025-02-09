<?php
function registrarActividad($con, $usuarioId, $actividad, $descripcion) {
    $query = "INSERT INTO actividades (usuarioId, actividad,descripcion) VALUES (?, ?,?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param('iss', $usuarioId, $actividad,$descripcion);
    $stmt->execute();
    $stmt->close();
}
?>