<?php
session_start(); // Iniciar la sesión
include "coneccionbd.php";
// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: /cliente/login.php");
    exit();
}
$todo = $_SESSION['user_id'];
// Inicializar la variable de búsqueda
$searchVenId = isset($_POST['searchVenId']) ? $con->real_escape_string(trim($_POST['searchVenId'])) : '';

// Consultas SQL para las diferentes compras, incluyendo búsqueda
$comprasCompletadas = $con->query("SELECT ventas.venId, producto.proNombre, stock.colId, stock.talId, detalleventa.detVenCantidad, seguimiento_compra.segEstadoEnvio FROM detalleventa 
INNER JOIN ventas on ventas.venId=detalleventa.venId
INNER JOIN seguimiento_compra on seguimiento_compra.venId=ventas.venId
INNER JOIN stock on stock.stoId=detalleventa.stoId
INNER JOIN producto on producto.proId=stock.proId
INNER JOIN cliente on cliente.cliId=ventas.cliId
INNER JOIN estadoventa on estadoventa.estVenId=ventas.estVenId
WHERE cliente.cliId=$todo AND ventas.estVenId=1" . ($searchVenId ? " AND ventas.venId='$searchVenId'" : ""));

$comprasPendientes = $con->query("SELECT ventas.venId, producto.proNombre, stock.colId, stock.talId, detalleventa.detVenCantidad, seguimiento_compra.segEstadoEnvio FROM detalleventa 
INNER JOIN ventas on ventas.venId=detalleventa.venId
INNER JOIN seguimiento_compra on seguimiento_compra.venId=ventas.venId
INNER JOIN stock on stock.stoId=detalleventa.stoId
INNER JOIN producto on producto.proId=stock.proId
INNER JOIN cliente on cliente.cliId=ventas.cliId
INNER JOIN estadoventa on estadoventa.estVenId=ventas.estVenId
WHERE cliente.cliId=$todo AND ventas.estVenId=2" . ($searchVenId ? " AND ventas.venId='$searchVenId'" : ""));

$comprasCanceladas = $con->query("SELECT ventas.venId, producto.proNombre, stock.colId, stock.talId, detalleventa.detVenCantidad, seguimiento_compra.segEstadoEnvio FROM detalleventa 
INNER JOIN ventas on ventas.venId=detalleventa.venId
INNER JOIN seguimiento_compra on seguimiento_compra.venId=ventas.venId
INNER JOIN stock on stock.stoId=detalleventa.stoId
INNER JOIN producto on producto.proId=stock.proId
INNER JOIN cliente on cliente.cliId=ventas.cliId
INNER JOIN estadoventa on estadoventa.estVenId=ventas.estVenId
WHERE cliente.cliId=$todo AND ventas.estVenId=3" . ($searchVenId ? " AND ventas.venId='$searchVenId'" : ""));

// Determinar qué tab mostrar
$activeTab = 'completadas'; // Valor por defecto
if ($comprasPendientes->num_rows > 0) {
    $activeTab = 'pendientes';
} elseif ($comprasCanceladas->num_rows > 0) {
    $activeTab = 'canceladas';
} elseif ($comprasCompletadas->num_rows > 0) {
    $activeTab = 'completadas';
} else {
    $activeTab = 'completadas'; // Si no hay resultados, mostrar completadas por defecto
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Compras del Cliente</title>
</head>
<body>
<div class="container mt-5">
    <h2>Compras del Cliente</h2>
    
    <!-- Formulario de búsqueda -->
    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="searchVenId" class="form-control" placeholder="Buscar por Venta ID" value="<?= htmlspecialchars($searchVenId) ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= $activeTab == 'completadas' ? 'active' : '' ?>" id="completadas-tab" data-toggle="tab" href="#completadas" role="tab" aria-controls="completadas" aria-selected="<?= $activeTab == 'completadas' ? 'true' : 'false' ?>">Completadas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab == 'pendientes' ? 'active' : '' ?>" id="pendientes-tab" data-toggle="tab" href="#pendientes" role="tab" aria-controls="pendientes" aria-selected="<?= $activeTab == 'pendientes' ? 'true' : 'false' ?>">Pendientes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activeTab == 'canceladas' ? 'active' : '' ?>" id="canceladas-tab" data-toggle="tab" href="#canceladas" role="tab" aria-controls="canceladas" aria-selected="<?= $activeTab == 'canceladas' ? 'true' : 'false' ?>">Canceladas</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade <?= $activeTab == 'completadas' ? 'show active' : '' ?>" id="completadas" role="tabpanel" aria-labelledby="completadas-tab">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Venta ID</th>
                        <th>Producto</th>
                        <th>Color</th>
                        <th>Talla</th>
                        <th>Cantidad</th>
                        <th>Estado de envío</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $comprasCompletadas->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['venId'] ?></td>
                            <td><?= $row['proNombre'] ?></td>
                            <td><?= $row['colId'] ?></td>
                            <td><?= $row['talId'] ?></td>
                            <td><?= $row['detVenCantidad'] ?></td>
                            <td><?= $row['segEstadoEnvio'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade <?= $activeTab == 'pendientes' ? 'show active' : '' ?>" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Venta ID</th>
                        <th>Producto</th>
                        <th>Color</th>
                        <th>Talla</th>
                        <th>Cantidad</th>
                        <th>Estado de envío</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $comprasPendientes->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['venId'] ?></td>
                            <td><?= $row['proNombre'] ?></td>
                            <td><?= $row['colId'] ?></td>
                            <td><?= $row['talId'] ?></td>
                            <td><?= $row['detVenCantidad'] ?></td>
                            <td><?= $row['segEstadoEnvio'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade <?= $activeTab == 'canceladas' ? 'show active' : '' ?>" id="canceladas" role="tabpanel" aria-labelledby="canceladas-tab">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Venta ID</th>
                        <th>Producto</th>
                        <th>Color</th>
                        <th>Talla</th>
                        <th>Cantidad</th>
                        <th>Estado de envío</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $comprasCanceladas->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['venId'] ?></td>
                            <td><?= $row['proNombre'] ?></td>
                            <td><?= $row['colId'] ?></td>
                            <td><?= $row['talId'] ?></td>
                            <td><?= $row['detVenCantidad'] ?></td>
                            <td><?= $row['segEstadoEnvio'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$con->close();
?>