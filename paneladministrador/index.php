<?php
include "coneccionbd.php";
include "header.php";
include "sidebar.php";

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['admin_username'];

// Función para obtener el conteo de una tabla
function obtenerConteo($con, $tabla) {
    $query = "SELECT COUNT(*) AS total FROM $tabla";
    $result = mysqli_query($con, $query);
    return mysqli_fetch_assoc($result)['total'];
}

// Obtener los conteos
$totalCategorias = obtenerConteo($con, 'categoria');
$totalProductos = obtenerConteo($con, 'producto');
$totalResenhas = obtenerConteo($con, 'resenhas');
$totalStocks = obtenerConteo($con, 'stock');
$totalTallas = obtenerConteo($con, 'talla');
$totalColores = obtenerConteo($con, 'color');
$totalClientes = obtenerConteo($con, 'cliente');
$totalOfertas = obtenerConteo($con, 'oferta');

// Consultas para obtener los datos necesarios
$queryCantidadVentas = "SELECT COUNT(*) AS cantidad FROM ventas v INNER JOIN detalleventa dv ON dv.venId = v.venId";
$resultCantidad = mysqli_query($con, $queryCantidadVentas);
$cantidadVentas = mysqli_fetch_assoc($resultCantidad)['cantidad'];

$queryTotalVentas = "SELECT SUM(detVenPrecio * detVenCantidad) AS total FROM detalleventa";
$resultTotal = mysqli_query($con, $queryTotalVentas);
$totalVentas = mysqli_fetch_assoc($resultTotal)['total'];

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

// Función para generar las tarjetas de reporte
function generarTarjetaReporte($titulo, $valor, $imagen, $enlace) {
    return "
    <div class='col-lg-3 col-md-6'>
        <div class='card border-0 shadow-sm text-center card-report-hover'>
            <div class='card-body p-2'>
                <div class='mb-3 mt-3'>
                   <img src='$imagen' alt='$titulo'>
                </div>
                <h6 class='card-title'>$titulo</h6>
                <p class='card-text fw-bold'>" . htmlspecialchars($valor) . "</p>
                <div class='position-absolute top-0 end-0 p-2'>
                    <a href='$enlace'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 256 256'>
                            <path fill='currentColor' d='M224 104a8 8 0 0 1-16 0V59.32l-66.33 66.34a8 8 0 0 1-11.32-11.32L196.68 48H152a8 8 0 0 1 0-16h64a8 8 0 0 1 8 8Zm-40 24a8 8 0 0 0-8 8v72H48V80h72a8 8 0 0 0 0-16H48a16 16 0 0 0-16 16v128a16 16 0 0 0 16 16h128a16 16 0 0 0 16-16v-72a8 8 0 0 0-8-8' />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>";
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 text-primary">Tablero</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tableros</a></li>
                                <li class="breadcrumb-item active">Tablero</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header bg-primary text-white rounded-top-4 mb-3">
                <h5 class="card-title mb-0 text-center text-white">Reportes Generales</h5>
            </div>

            <div class="row justify-content-center">
                <?php
                echo generarTarjetaReporte('Categorias', $totalCategorias, 'recursos/images/tablero/categories.png', 'reportes/categorias/reporte-categorias.php');
                echo generarTarjetaReporte('Productos', $totalProductos, 'recursos/images/tablero/productos.png', 'reportes/productos/reporte-productos.php');
                echo generarTarjetaReporte('Stocks', $totalStocks, 'recursos/images/tablero/stock.png', 'reportes/stocks/reporte-stocks.php');
                echo generarTarjetaReporte('Ofertas', $totalOfertas, 'recursos/images/tablero/ofertas.png', 'reportes/ofertas/reporte-ofertas.php');
                echo generarTarjetaReporte('Tallas', $totalTallas, 'recursos/images/tablero/tallas.png', 'reportes/tallas/reporte-tallas.php');
                echo generarTarjetaReporte('Colores', $totalColores, 'recursos/images/tablero/colores.png', 'reportes/colores/reporte-colores.php');
                echo generarTarjetaReporte('Clientes', $totalClientes, 'recursos/images/tablero/clientes.png', 'reportes/clientes/reporte-clientes.php');
                echo generarTarjetaReporte('Reseñas', $totalResenhas ?: '0', 'recursos/images/tablero/reseñas.png', 'reportes/resenhas/reporte-resenhas.php');
                ?>
            </div>

            <div class="col-12 mt-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4 py-3">
                        <h5 class="card-title mb-0 text-center text-white">Reportes de Ventas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm text-center h-100">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-cart-check-fill text-success fs-1"></i>
                                        </div>
                                        <h5 class="card-title">Cantidad de Ventas</h5>
                                        <p class="card-text display-6 fw-bold" id="cantidad-ventas">
                                            <?php echo htmlspecialchars($cantidadVentas); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm text-center h-100">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-currency-dollar text-primary fs-1"></i>
                                        </div>
                                        <h5 class="card-title">Total Ventas</h5>
                                        <p class="card-text display-6 fw-bold text-primary" id="total-ventas">
                                            <?php echo '$' . number_format($totalVentas, 2); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm text-center h-100">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-bag-heart-fill text-danger fs-1"></i>
                                        </div>
                                        <h5 class="card-title">Producto Más Vendido</h5>
                                        <p class="card-text display-6 fw-bold text-danger" id="producto-mas-vendido">
                                            <?php echo htmlspecialchars($productoFrecuente); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4 mx-auto">
                                <div class="card border-0 shadow-sm text-center h-100">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-person-fill text-warning fs-1"></i>
                                        </div>
                                        <h5 class="card-title">Cliente Más Frecuente</h5>
                                        <p class="card-text display-6 fw-bold text-warning" id="cliente-frecuente">
                                            <?php echo htmlspecialchars($clienteFrecuente); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include "footer.php"; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
