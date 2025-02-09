<?php
include "../../coneccionbd.php";

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 15;
$offset = ($pagina - 1) * $registrosPorPagina;

// Obtener el total de registros
$queryTotal = "SELECT COUNT(*) AS total FROM actividades";
$resultTotal = mysqli_query($con, $queryTotal);
$totalRegistros = mysqli_fetch_assoc($resultTotal)['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Obtener las actividades recientes de los usuarios con paginación
$queryActividades = "
    SELECT a.actId,a.descripcion, u.admUser AS usuario, a.actividad, a.fecha 
    FROM actividades a 
    INNER JOIN usuario u ON a.usuarioId = u.admId 
    ORDER BY a.fecha DESC 
    LIMIT $registrosPorPagina OFFSET $offset";
$resultActividades = mysqli_query($con, $queryActividades);
$actividades = [];
while ($row = mysqli_fetch_assoc($resultActividades)) {
    $actividades[] = [
        'actId' => $row['actId'],
        'usuario' => $row['usuario'],
        'actividad' => $row['actividad'],
        'descripcion' => $row['descripcion'],
        'fecha' => $row['fecha']
    ];
}

echo json_encode([
    'actividades' => $actividades,
    'total' => $totalRegistros,
    'totalPaginas' => $totalPaginas
]);
?>