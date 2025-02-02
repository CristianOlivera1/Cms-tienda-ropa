<?php
include "coneccionbd.php";
include "header.php";
include "sidebar.php"; 

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['admin_username'];

// Consultas para obtener el total de categorías y productos
$queryCategorias = "SELECT COUNT(*) AS totalCategorias FROM categoria";
$resultCategorias = mysqli_query($con, $queryCategorias);
$totalCategorias = mysqli_fetch_assoc($resultCategorias)['totalCategorias'];

$queryProductos = "SELECT COUNT(*) AS totalProductos FROM producto";
$resultProductos = mysqli_query($con, $queryProductos);
$totalProductos = mysqli_fetch_assoc($resultProductos)['totalProductos'];

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

?>

<style>
    body {
        background-color: #f4f7f9;
        font-family: 'Poppins', sans-serif;
    }
    .main-content {
        min-height: 100vh;
        padding: 20px;
    }
    .card {
        border-radius: 15px;
        transition: transform 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .avatar-lg {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        border-radius: 50%;
    }
    .page-title-box h4 {
        font-weight: 700;
    }
</style>

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
            
            <div class="row">
                <div class="col text-center">
                    <h4 class="fs-16 mb-1 text-dark">Hola, <?php echo htmlspecialchars($username); ?>!</h4>
                    <p class="text-muted mb-4">Bienvenido de nuevo a tu tablero de administración.</p>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="ri-git-merge-fill text-primary fs-1"></i>
                </div>
                <h5 class="card-title">Total de Categorías</h5>
                <p class="card-text display-6 fw-bold"><?php echo htmlspecialchars($totalCategorias); ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="ri-server-line text-success fs-1"></i>
                </div>
                <h5 class="card-title">Total de Productos</h5>
                <p class="card-text display-6 fw-bold"><?php echo htmlspecialchars($totalProductos); ?></p>
            </div>
        </div>
    </div>
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