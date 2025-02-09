<?php
include "../../coneccionbd.php";

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 15;
$offset = ($pagina - 1) * $registrosPorPagina;

$usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';
$actividad = isset($_GET['actividad']) ? $_GET['actividad'] : '';
$fechaDesde = isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : '';
$fechaHasta = isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : '';

// Construir la consulta con los filtros
$queryTotal = "SELECT COUNT(*) AS total FROM actividades a INNER JOIN usuario u ON a.usuarioId = u.admId WHERE 1=1";
$queryActividades = "SELECT a.actId, a.descripcion, u.admUser AS usuario, a.nombreTabla, a.actividad, a.fecha FROM actividades a INNER JOIN usuario u ON a.usuarioId = u.admId WHERE 1=1";


$params = [];
if ($usuario) {
    $queryTotal .= " AND u.admUser LIKE ?";
    $queryActividades .= " AND u.admUser LIKE ?";
    $params[] = "%$usuario%";
}
if ($tabla) {
    $queryTotal .= " AND a.nombreTabla = ?";
    $queryActividades .= " AND a.nombreTabla = ?";
    $params[] = $tabla;
}
if ($actividad) {
    $queryTotal .= " AND a.actividad = ?";
    $queryActividades .= " AND a.actividad = ?";
    $params[] = $actividad;
}
if ($fechaDesde) {
    $queryTotal .= " AND a.fecha >= ?";
    $queryActividades .= " AND a.fecha >= ?";
    $params[] = $fechaDesde;
}
if ($fechaHasta) {
    $queryTotal .= " AND a.fecha <= ?";
    $queryActividades .= " AND a.fecha <= ?";
    $params[] = $fechaHasta;
}

$queryActividades .= " ORDER BY a.fecha DESC LIMIT $registrosPorPagina OFFSET $offset";

// Preparar y ejecutar la consulta para obtener el total de registros
$stmtTotal = $con->prepare($queryTotal);
if ($params) {
    $stmtTotal->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$totalRegistros = $resultTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Preparar y ejecutar la consulta para obtener las actividades
$stmtActividades = $con->prepare($queryActividades);
if ($params) {
    $stmtActividades->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmtActividades->execute();
$resultActividades = $stmtActividades->get_result();

$actividades = [];
while ($row = mysqli_fetch_assoc($resultActividades)) {
    $actividades[] = [
        'actId' => $row['actId'],
        'usuario' => $row['usuario'],
        'nombreTabla' => $row['nombreTabla'],
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