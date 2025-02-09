<?php
function registrarActividad($con, $usuarioId,$nombreTabla, $actividad, $descripcion) {
    $query = "INSERT INTO actividades (usuarioId,nombreTabla, actividad,descripcion) VALUES (?, ?,?,?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param('isss', $usuarioId,$nombreTabla, $actividad,$descripcion);
    $stmt->execute();
    $stmt->close();
}
?>