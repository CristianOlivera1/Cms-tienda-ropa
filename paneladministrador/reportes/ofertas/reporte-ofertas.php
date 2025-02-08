<?php
include "../../header.php";
include "../../sidebar.php";

// Eliminar ofertas expiradas
$query_delete_expired = "DELETE FROM oferta WHERE ofeTiempo < NOW()";
$con->query($query_delete_expired); // Ejecuta la consulta

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_param = "%$search%";

// Inicializar la variable
$total_registros = 0;

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM oferta WHERE ofePorcentaje LIKE ?";
$params = [$search_param];

$stmt_total = $con->prepare($query_total);
$stmt_total->bind_param('s', $params[0]);
$stmt_total->execute();
$result_total = $stmt_total->get_result();

if ($result_total) {
    $total_registros = $result_total->fetch_assoc()['total'];
}
$total_paginas = ceil($total_registros / $registros_por_pagina);


// Obtener ofertas con filtros y paginación
$query = "
SELECT p.proNombre, o.ofeId, s.stoId, o.ofePorcentaje, o.ofeTiempo, o.ofeFechaRegis, p.proPrecio AS precioNormal,
       (p.proPrecio * (1 - o.ofePorcentaje / 100)) AS precioDescontado
FROM oferta o 
INNER JOIN stock s ON o.stoId = s.stoId 
INNER JOIN producto p ON s.proId = p.proId 
WHERE (o.ofePorcentaje LIKE ?)
ORDER BY o.ofeId DESC LIMIT $registros_por_pagina OFFSET $offset";

$stmt = $con->prepare($query);
$stmt->bind_param('s', $params[0]);
$stmt->execute();
$result = $stmt->get_result();

// Obtener datos para el gráfico de Ofertas por Terminarse
$query_terminarse = "
SELECT p.proNombre, COUNT(o.ofeId) as cantidad
FROM oferta o 
INNER JOIN stock s ON o.stoId = s.stoId 
INNER JOIN producto p ON s.proId = p.proId 
WHERE o.ofeTiempo <= DATE_ADD(NOW(), INTERVAL 3 DAY)
GROUP BY p.proNombre";
$result_terminarse = mysqli_query($con, $query_terminarse);
$productos_terminarse = [];
$cantidades_terminarse = [];
while ($row = mysqli_fetch_assoc($result_terminarse)) {
    $productos_terminarse[] = $row['proNombre'];
    $cantidades_terminarse[] = $row['cantidad'];
}
// Obtener datos para el gráfico de Ofertas por Categoría
$query_categoria = "
SELECT c.catNombre, COUNT(o.ofeId) as cantidad
FROM oferta o 
INNER JOIN stock s ON o.stoId = s.stoId 
INNER JOIN producto p ON s.proId = p.proId 
INNER JOIN categoria c ON p.catId = c.catId
GROUP BY c.catNombre";
$result_categoria = mysqli_query($con, $query_categoria);
$categorias = [];
$cantidades_categoria = [];
while ($row = mysqli_fetch_assoc($result_categoria)) {
    $categorias[] = $row['catNombre'];
    $cantidades_categoria[] = $row['cantidad'];
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reporte de Ofertas</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Ofertas</a></li>
                                <li class="breadcrumb-item active">Reporte</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestañas para los gráficos -->
            <div class="row" style="background-color: white;">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="terminarse-tab" data-bs-toggle="tab" data-bs-target="#terminarse" type="button" role="tab" aria-controls="terminarse" aria-selected="true">Ofertas por Terminarse</button>
                        </li>
                        <li class="nav-item" role="presentation">
                           <button class="nav-link" id="categoria-tab" data-bs-toggle="tab" data-bs-target="#categoria" type="button" role="tab" aria-controls="categoria" aria-selected="false">Ofertas por Categoría</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="terminarse" role="tabpanel" aria-labelledby="terminarse-tab">
                            <canvas id="terminarseChart" class="mt-4"></canvas>
                        </div>
                        <div class="tab-pane fade" id="categoria" role="tabpanel" aria-labelledby="categoria-tab">
                            <canvas id="categoriaChart" class="mt-4"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de ofertas -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lista de Ofertas <div class="badge-total">Total: <?php echo $total_registros; ?></div></h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="" id="filterForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" id="search" class="form-control" placeholder="Buscar por porcentaje" value="<?php echo htmlspecialchars($search); ?>" onkeyup="if(event.keyCode == 13) window.location.href='?search='+this.value">
                                    <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                    </form>
                            <!-- Paginación -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                        <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>#example"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                            </div>
                        </div>
                        <!-- Tabla de ofertas -->
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Producto</th>
                                    <th>Porcentaje</th>
                                    <th>Tiempo de Oferta</th>
                                    <th>Precio normal</th>
                                    <th>Precio oferta</th>
                                    <th>Fecha de registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $numero = $offset + 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr id='oferta-{$row['ofeId']}'>
                                            <td>{$numero}</td>
                                            <td>{$row['proNombre']}</td>
                                            <td>{$row['ofePorcentaje']}%</td>
                                            <td>{$row['ofeTiempo']}</td>
                                            <td>" . number_format($row['precioNormal'], 2) . " Soles</td>
                                            <td>" . number_format($row['precioDescontado'], 2) . " Soles</td>
                                            <td>{$row['ofeFechaRegis']}</td>
                                        </tr>";
                                    $numero++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Ofertas por Terminarse
    var ctxTerminarse = document.getElementById('terminarseChart').getContext('2d');
    var terminarseChart = new Chart(ctxTerminarse, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($productos_terminarse); ?>,
            datasets: [{
                label: 'Ofertas por Terminarse',
                data: <?php echo json_encode($cantidades_terminarse); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
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

    // Gráfico de Ofertas por Categoría
    var ctxCategoria = document.getElementById('categoriaChart').getContext('2d');
    var categoriaChart = new Chart(ctxCategoria, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($categorias); ?>,
            datasets: [{
                label: 'Ofertas por Categoría',
                data: <?php echo json_encode($cantidades_categoria); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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
</script>
<script src="../../recursos/js/script.js"></script>
<?php include "../../footer.php"; ?>