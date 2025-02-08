<?php
include "../../header.php";
include "../../sidebar.php";

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Actualizar la consulta para incluir el término de búsqueda
$query_total = "SELECT COUNT(*) as total FROM categoria WHERE catNombre LIKE ? OR catDescripcion LIKE ?";
$stmt_total = $con->prepare($query_total);
$search_param = "%$search%";
$stmt_total->bind_param('ss', $search_param, $search_param);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener las categorías con el término de búsqueda y la paginación
$query = "SELECT * FROM categoria WHERE catNombre LIKE ? OR catDescripcion LIKE ? ORDER BY catId DESC LIMIT ? OFFSET ?";
$stmt = $con->prepare($query);
$stmt->bind_param('ssii', $search_param, $search_param, $registros_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

//Reporte
// Obtener datos para el gráfico de barras (Cantidad de Productos por Categoría)
$query_bar = "SELECT c.catNombre, COUNT(p.proId) as cantidad FROM categoria c 
              LEFT JOIN producto p ON c.catId = p.catId 
              GROUP BY c.catNombre";
$result_bar = mysqli_query($con, $query_bar);
$categorias = [];
$cantidades = [];
while ($row = mysqli_fetch_assoc($result_bar)) {
    $categorias[] = $row['catNombre'];
    $cantidades[] = $row['cantidad'];
}

?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reporte de categorías por fechas</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Categorías</a></li>
                                <li class="breadcrumb-item active">Reporte</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i class="fas fa-tags"></i> Productos por categorías 
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Categorías<div class="badge-total">Total: <?php echo $total_registros; ?></div></h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="search" name="search" class="form-control" placeholder="Buscar categoría" value="<?php echo htmlspecialchars($search); ?>">
                                        <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                    <!-- Paginación -->
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                                <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Nombre de la Categoría</th>
                                        <th>Descripción</th>
                                        <th>Imagen</th>
                                        <th>Fecha de registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $numeracion = $offset + 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $image_path = "../../recursos/uploads/categoria/" . htmlspecialchars($row['catImg']);
                                        echo "<tr id='categoria-{$row['catId']}'>
                                                <td>{$numeracion}</td>
                                                <td>" . htmlspecialchars($row['catNombre']) . "</td>
                                                <td>" . htmlspecialchars($row['catDescripcion']) . "</td>
                                                <td><img src='" . $image_path . "' alt='" . htmlspecialchars($row['catNombre']) . "' style='width: 50px; height: 50px;'></td>
                                                <td>" . htmlspecialchars($row['catFechaRegis']) . "</td>
                                            </tr>";
                                        $numeracion++;
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
    <script src="../../recursos/js/script.js"></script>
</div>
<?php include "../../footer.php"; ?>

<script>
    // Gráfico de Barras: Cantidad de Productos por Categoría
    var ctxBar = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($categorias); ?>,
            datasets: [{
                label: 'Cantidad de Productos por categorias',
                data: <?php echo json_encode($cantidades); ?>,
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

</script>

<?php include "../../footer.php"; ?>