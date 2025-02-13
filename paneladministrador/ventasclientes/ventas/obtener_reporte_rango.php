<?php
include "../../coneccionbd.php"; // Asegúrate de incluir la conexión a la base de datos

// Obtener las fechas del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$fecha_desde = $data['fecha_desde'];
$fecha_hasta = $data['fecha_hasta'];

// Consulta SQL para obtener las ventas en el rango de fechas
$query = "
    SELECT 
        v.venId, 
        c.cliNombre, 
        CONCAT(c.cliApellidoPaterno, ' ', c.cliApellidoMaterno) AS Apellidos, 
        c.cliCorreo, 
        c.cliDni,
        SUM(d.detVenCantidad * d.detVenPrecio) AS total, 
        v.venFechaRegis,
        e.estVenNombre AS Estado
    FROM detalleventa d 
    INNER JOIN ventas v ON d.venId = v.venId 
    INNER JOIN cliente c ON v.cliId = c.cliId 
    INNER JOIN estadoventa e ON v.estVenId = e.estVenId
    WHERE v.venFechaRegis BETWEEN ? AND ?
    GROUP BY v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, c.cliDni, v.venFechaRegis, e.estVenNombre";

$stmt = $con->prepare($query);
$stmt->bind_param('ss', $fecha_desde, $fecha_hasta);
$stmt->execute();
$result = $stmt->get_result();

$ventas = [];
while ($row = $result->fetch_assoc()) {
    $ventas[] = $row;
}

echo json_encode(['ventas' => $ventas]);
?>