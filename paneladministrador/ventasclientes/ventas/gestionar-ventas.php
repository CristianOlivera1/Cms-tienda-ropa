<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda y filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Obtener el total de registros
$query_total = "
    SELECT COUNT(*) as total 
    FROM ventas v 
    INNER JOIN cliente c ON v.cliId = c.cliId 
    INNER JOIN detalleventa dv ON v.venId = dv.venId 
    INNER JOIN stock s ON dv.stoId = s.stoId 
    INNER JOIN producto p ON s.proId = p.proId 
    WHERE c.cliNombre LIKE ?";
    
$stmt_total = $con->prepare($query_total);
$search_param = "%$search%";
$stmt_total->bind_param('s', $search_param);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consultas para obtener los datos necesarios
$queryCantidadVentas = "SELECT COUNT(*) AS cantidad FROM ventas v inner join detalleventa dv on dv.venId=v.venId";
$resultCantidad = mysqli_query($con, $queryCantidadVentas);
$cantidadVentas = mysqli_fetch_assoc($resultCantidad)['cantidad'];

$queryTotalVentas = "SELECT SUM(detVenPrecio * detVenCantidad) AS total FROM detalleventa";
$resultTotal = mysqli_query($con, $queryTotalVentas);
$totalVentas = mysqli_fetch_assoc($resultTotal)['total'];

// Obtener el producto más frecuente
$queryProductoFrecuente = "
    SELECT p.proNombre 
    FROM detalleventa dv 
    INNER JOIN stock s ON dv.stoId = s.stoId 
    INNER JOIN producto p ON s.proId = p.proId 
    GROUP BY p.proId 
    ORDER BY SUM(dv.detVenCantidad) DESC 
    LIMIT 1";
$resultProducto = mysqli_query($con, $queryProductoFrecuente);
$productoFrecuente = ($resultProducto && mysqli_num_rows($resultProducto) > 0) ? mysqli_fetch_assoc($resultProducto)['proNombre'] : 'N/A';

$queryClienteFrecuente = "
    SELECT c.cliNombre 
    FROM ventas v 
    INNER JOIN cliente c ON v.cliId = c.cliId 
    GROUP BY v.cliId 
    ORDER BY COUNT(v.cliId) DESC 
    LIMIT 1";
$resultCliente = mysqli_query($con, $queryClienteFrecuente);
$clienteFrecuente = ($resultCliente && mysqli_num_rows($resultCliente) > 0) ? mysqli_fetch_assoc($resultCliente)['cliNombre'] : 'N/A';
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Gestión de Ventas</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item active">Detalles de Ventas</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="card">
                <div class="col-12">
                    
                        <div class="card-header my-3">
                            <h5 class="card-title mb-0">Reportes de Ventas</h5>
                        </div>
                    

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Cantidad de Ventas</h5>
                                    <p class="card-text" id="cantidad-ventas"><?php echo htmlspecialchars($cantidadVentas); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Ventas</h5>
                                    <p class="card-text" id="total-ventas"><?php echo '$' . number_format($totalVentas, 2); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Producto Más Vendido</h5>
                                    <p class="card-text" id="producto-mas-vendido"><?php echo htmlspecialchars($productoFrecuente); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Cliente Más Frecuente</h5>
                                    <p class="card-text" id="cliente-frecuente"><?php echo htmlspecialchars($clienteFrecuente); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Ventas</h5>
                        </div>

                    <div class="card-body">
                        <table class="table table-hover" id="detallesTable">
                            <thead>
                                <tr>
                                    <th>Codigo de venta</th>
                                    <th>Nombre del cliente</th>
                                    <th>Apellido Paterno</th>
                                    <th>Apellido Materno</th>
                                    <th>Correo</th>
                                    <th>Precio Total</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Consulta ajustada para obtener solo el ID y el total
                                $query = "SELECT v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, SUM(d.detVenCantidad * d.detVenPrecio) AS total, v.venFechaRegis FROM detalleventa d INNER JOIN ventas v ON d.venId = v.venId INNER JOIN cliente c ON v.cliId = c.cliId GROUP BY v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, v.venFechaRegis;";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "
                                    <tr id='detalle-{$row['venId']}'>
                                        <td>{$row['venId']}</td>
                                        <td>{$row['cliNombre']}</td>
                                        <td>{$row['cliApellidoPaterno']}</td>
                                        <td>{$row['cliApellidoMaterno']}</td>
                                        <td>{$row['cliCorreo']}</td>
                                        <td>{$row['total']}</td>
                                        <td>{$row['venFechaRegis']}</td>
                                        <td>
                                            <a href='ver-detalles-venta.php?venId={$row['venId']}' class='btn btn-info'>Ver Detalles</a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../footer.php"; ?>