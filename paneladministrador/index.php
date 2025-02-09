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
$totalProductos = obtenerConteo($con, 'producto');
$totalStocks = obtenerConteo($con, 'stock');
$totalUsuarios = obtenerConteo($con, 'usuario');
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
            <!-- Encabezado del Tablero -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 text-primary fw-bold">Tablero</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);" class="text-muted">Tableros</a></li>
                                <li class="breadcrumb-item active text-primary fw-bold">Tablero</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Reportes Generales -->
            <div class="card shadow-lg border-0 rounded-4 mt-4">
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h5 class="card-title mb-0 text-center text-white">Reportes Generales</h5>
                </div>
                <div class="card-body p-4 mt-3">
                    <div class="row g-4 justify-content-center">
                        <?php
                        echo generarTarjetaReporte('Productos', $totalProductos, 'recursos/images/tablero/productos.png', 'reportes/productos/reporte-productos.php');
                        echo generarTarjetaReporte('Stocks', $totalStocks, 'recursos/images/tablero/stock.png', 'reportes/stocks/reporte-stocks.php');
                        echo generarTarjetaReporte('Ofertas', $totalOfertas, 'recursos/images/tablero/ofertas.png', 'reportes/ofertas/reporte-ofertas.php');
                        echo generarTarjetaReporte('Usuarios', $totalUsuarios, 'recursos/images/tablero/usuarios.png', 'reportes/usuarios/reporte-usuarios.php');
                        ?>
                    </div>
                </div>
            </div>

            <!-- Sección Reportes de Ventas -->
            <div class="col-12 mt-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4 py-3">
                        <h5 class="card-title mb-0 text-center text-white">Reportes de Ventas</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Filtros de Fecha -->
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100 rounded-pill shadow-sm" onclick="cargarReporte('dia')">Día</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100 rounded-pill shadow-sm" onclick="cargarReporte('semana')">Esta Semana</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100 rounded-pill shadow-sm" onclick="cargarReporte('mes')">Mes</button>
                            </div>
                        </div>
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <label for="fecha_desde" class="form-label text-muted">Desde:</label>
                                <div class="input-group">
                                    <input type="date" id="fecha_desde" class="form-control rounded-pill shadow-sm">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_hasta" class="form-label text-muted">Hasta:</label>
                                <div class="input-group">
                                    <input type="date" id="fecha_hasta" class="form-control rounded-pill shadow-sm">
                                
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-secondary rounded-pill shadow-sm w-100" onclick="cargarReporte('rango')">Filtrar</button>
                            </div>
                        </div>

                        <!-- Métricas de Ventas -->
                        <div class="row g-4 mt-2">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm text-center h-100 hover-scale">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-cart-check-fill text-success fs-1"></i>
                                        </div>
                                        <h5 class="card-title text-muted">Cantidad de Ventas</h5>
                                        <p class="card-text display-6 fw-bold text-dark" id="cantidad-ventas">
                                            <?php echo htmlspecialchars($cantidadVentas); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm text-center h-100 hover-scale">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-currency-dollar text-primary fs-1"></i>
                                        </div>
                                        <h5 class="card-title text-muted">Total Ventas</h5>
                                        <p class="card-text display-6 fw-bold text-primary" id="total-ventas">
                                            <?php echo '$' . number_format($totalVentas, 2); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm text-center h-100 hover-scale">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-bag-heart-fill text-danger fs-1"></i>
                                        </div>
                                        <h5 class="card-title text-muted">Producto Más Vendido</h5>
                                        <p class="card-text display-6 fw-bold text-danger" id="producto-mas-vendido">
                                            <?php echo htmlspecialchars($productoFrecuente); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm text-center h-100 hover-scale">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="bi bi-person-fill text-warning fs-1"></i>
                                        </div>
                                        <h5 class="card-title text-muted">Cliente Más Frecuente</h5>
                                        <p class="card-text display-6 fw-bold text-warning" id="cliente-frecuente">
                                            <?php echo htmlspecialchars($clienteFrecuente); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Ventas -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <canvas id="ventasChart"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function cargarReporte(periodo) {
        let url = 'obtener_reporte.php';
        let params = {};

        if (periodo === 'rango') {
            params.fecha_desde = document.getElementById('fecha_desde').value;
            params.fecha_hasta = document.getElementById('fecha_hasta').value;
        } else {
            params.periodo = periodo;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(params)
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('cantidad-ventas').innerText = data.cantidadVentas ?? 0;
            document.getElementById('total-ventas').innerText = '$' + (parseFloat(data.totalVentas) || 0).toFixed(2);
            document.getElementById('producto-mas-vendido').innerText = data.productoFrecuente ?? 'N/A';
            document.getElementById('cliente-frecuente').innerText = data.clienteFrecuente ?? 'N/A';

            actualizarGrafico(data.grafico);
        })
        .catch(error => console.error('Error:', error));
    }

    let ventasChart;

    function actualizarGrafico(datos) {
        const ctx = document.getElementById('ventasChart').getContext('2d');
        
        if (ventasChart) {
            ventasChart.destroy();
        }

        ventasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: datos.labels,
                datasets: [{
                    label: 'Ventas',
                    data: datos.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
<?php include "footer.php"; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
