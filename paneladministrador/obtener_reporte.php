<?php
include "coneccionbd.php";

$data = json_decode(file_get_contents('php://input'), true);

$periodo = isset($data['periodo']) ? $data['periodo'] : '';
$fecha_desde = isset($data['fecha_desde']) ? $data['fecha_desde'] : '';
$fecha_hasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : '';

$condicion = '';
if ($periodo === 'dia') {
    $condicion = "WHERE DATE(v.venFechaRegis) = CURDATE()";
} elseif ($periodo === 'semana') {
    $condicion = "WHERE YEARWEEK(v.venFechaRegis, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($periodo === 'mes') {
    $condicion = "WHERE MONTH(v.venFechaRegis) = MONTH(CURDATE()) AND YEAR(v.venFechaRegis) = YEAR(CURDATE())";
} elseif ($fecha_desde && $fecha_hasta) {
    $condicion = "WHERE DATE(v.venFechaRegis) BETWEEN '$fecha_desde' AND '$fecha_hasta'";
}

// Consultas para obtener los datos necesarios
$queryCantidadVentas = "SELECT COUNT(*) AS cantidad FROM ventas v INNER JOIN detalleventa dv ON dv.venId = v.venId $condicion";
$resultCantidad = mysqli_query($con, $queryCantidadVentas);
$cantidadVentas = mysqli_fetch_assoc($resultCantidad)['cantidad'] ?? 0;

$queryTotalVentas = "SELECT SUM(dv.detVenPrecio * dv.detVenCantidad) AS total FROM detalleventa dv INNER JOIN ventas v ON dv.venId = v.venId $condicion";
$resultTotal = mysqli_query($con, $queryTotalVentas);
$totalVentas = mysqli_fetch_assoc($resultTotal)['total'] ?? 0;

$queryProductoFrecuente = "
    SELECT p.proNombre 
    FROM detalleventa dv 
    INNER JOIN stock s ON dv.stoId = s.stoId 
    INNER JOIN producto p ON s.proId = p.proId 
    INNER JOIN ventas v ON dv.venId = v.venId 
    $condicion
    GROUP BY p.proId 
    ORDER BY SUM(dv.detVenCantidad) DESC 
    LIMIT 1";
$resultProducto = mysqli_query($con, $queryProductoFrecuente);
$productoFrecuente = ($resultProducto && mysqli_num_rows($resultProducto) > 0) ? mysqli_fetch_assoc($resultProducto)['proNombre'] : 'N/A';

$queryClienteFrecuente = "
    SELECT c.cliNombre 
    FROM ventas v 
    INNER JOIN cliente c ON v.cliId = c.cliId 
    $condicion
    GROUP BY v.cliId 
    ORDER BY COUNT(v.cliId) DESC 
    LIMIT 1";
$resultCliente = mysqli_query($con, $queryClienteFrecuente);
$clienteFrecuente = ($resultCliente && mysqli_num_rows($resultCliente) > 0) ? mysqli_fetch_assoc($resultCliente)['cliNombre'] : 'N/A';

// Datos para el gráfico
$queryGrafico = "
    SELECT DATE(v.venFechaRegis) as fecha, SUM(dv.detVenPrecio * dv.detVenCantidad) as total
    FROM detalleventa dv 
    INNER JOIN ventas v ON dv.venId = v.venId 
    $condicion
    GROUP BY DATE(v.venFechaRegis)
    ORDER BY DATE(v.venFechaRegis)";
$resultGrafico = mysqli_query($con, $queryGrafico);
$labels = [];
$data = [];
while ($row = mysqli_fetch_assoc($resultGrafico)) {
    $labels[] = $row['fecha'];
    $data[] = $row['total'];
}

echo json_encode([
    'cantidadVentas' => $cantidadVentas,
    'totalVentas' => $totalVentas,
    'productoFrecuente' => $productoFrecuente,
    'clienteFrecuente' => $clienteFrecuente,
    'grafico' => [
        'labels' => $labels,
        'data' => $data
    ]
]);
?>