<?php
include "../../coneccionbd.php"; // Asegúrate de incluir el archivo de configuración para la conexión a la base de datos

// Obtener los datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$venId = $data['venId'] ?? null; // Obtener venId
$estado = $data['estado'] ?? null; // Obtener estado

if (!$venId || !$estado) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios']);
    exit;
}

// Mapeo de estados a IDs correspondientes
$estadoId = 1; // Por defecto, estado pendiente
if ($estado === 'Confirmado') {
    $estadoId = 2; // ID para estado "completada"
} else if ($estado === 'Cancelado') {
    $estadoId = 3; // ID para estado "cancelada"
}

// Actualizar la tabla ventas
$query = "UPDATE ventas SET estVenId = $estadoId WHERE venId = '$venId'";
$result = mysqli_query($con, $query);

if (!$result) {
    echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    exit;
}

// Actualizar la tabla seguimiento
if ($estado === 'Confirmado') {
    $querySeguimiento = "UPDATE seguimiento_compra SET segEstadoEnvio = 'En camino' WHERE venId = '$venId'";
    $resultSeguimiento = mysqli_query($con, $querySeguimiento);

    if (!$resultSeguimiento) {
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
        exit;
    }
}
if ($estado === 'Cancelado') {
    $querySeguimiento = "UPDATE seguimiento_compra SET segEstadoEnvio = 'Cancelado' WHERE venId = '$venId'";
    $resultSeguimiento = mysqli_query($con, $querySeguimiento);

    if (!$resultSeguimiento) {
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
        exit;
    }
}

echo json_encode(['success' => true]);
?>