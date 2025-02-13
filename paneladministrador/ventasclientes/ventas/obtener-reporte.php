<?php
include "../../header.php";
include "../../sidebar.php";

// Obtener el filtro de fecha
$filtro = isset($_POST['filtro']) ? $_POST['filtro'] : '';
$fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : '';
$fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : '';

// Consulta base
$query = "SELECT 
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
  INNER JOIN estadoventa e ON v.estVenId = e.estVenId";

// Aplicar filtros de fecha
if ($filtro === 'dia') {
    $query .= " WHERE DATE(v.venFechaRegis) = CURDATE()";
} elseif ($filtro === 'semana') {
    $query .= " WHERE YEARWEEK(v.venFechaRegis, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filtro === 'mes') {
    $query .= " WHERE MONTH(v.venFechaRegis) = MONTH(CURDATE()) AND YEAR(v.venFechaRegis) = YEAR(CURDATE())";
} elseif ($filtro === 'rango' && $fecha_desde && $fecha_hasta) {
    $query .= " WHERE DATE(v.venFechaRegis) BETWEEN '$fecha_desde' AND '$fecha_hasta'";
}

$query .= " GROUP BY v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, c.cliDni, v.venFechaRegis, e.estVenNombre;";

$result = mysqli_query($con, $query);
$ventas = [];

while ($row = mysqli_fetch_assoc($result)) {
    $ventas[] = $row;
}

echo json_encode(['ventas' => $ventas]);
?>