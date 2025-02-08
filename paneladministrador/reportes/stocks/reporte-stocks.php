<?php
include "../../header.php";
include "../../sidebar.php";

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda y filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filterCategory = isset($_GET['filterCategory']) ? $_GET['filterCategory'] : '';
$filterState = isset($_GET['filterState']) ? $_GET['filterState'] : '';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Definir el parámetro de búsqueda
$search_param = "%$search%";

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM stock s 
                INNER JOIN producto p ON s.proId=p.proId 
                INNER JOIN talla t ON s.talId=t.talId 
                INNER JOIN color c ON s.colId=c.colId 
                INNER JOIN estado e ON s.estId=e.estId 
                LEFT JOIN oferta o ON s.stoId = o.stoId
                WHERE (p.proNombre LIKE ? OR c.colNombre LIKE ? OR t.talNombre LIKE ? OR s.stoCantidad LIKE ?)";
$params = [$search_param, $search_param, $search_param, $search_param];

if ($filterCategory) {
    $query_total .= " AND p.catId = ?";
    $params[] = $filterCategory;
}

if ($filterState !== '') {
    $query_total .= " AND (e.estDisponible = ? OR (o.stoId IS NOT NULL AND ? = 'En oferta'))";
    $params[] = $filterState;
    $params[] = $filterState;
}

$stmt_total = $con->prepare($query_total);
$stmt_total->bind_param(str_repeat('s', count($params)), ...$params);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener datos para el gráfico de barras (Cantidad de Productos por Estado)
$query_estado = "SELECT e.estDisponible, COUNT(s.stoId) as cantidad FROM estado e 
                 LEFT JOIN stock s ON e.estId = s.estId 
                 GROUP BY e.estDisponible";
$result_estado = mysqli_query($con, $query_estado);
$estados = [];
$cantidades_estado = [];
while ($row2 = mysqli_fetch_assoc($result_estado)) {
    $estados[] = $row2['estDisponible'];
    $cantidades_estado[] = $row2['cantidad'];
}

// Obtener datos para el gráfico de líneas (Tendencia de Stocks por Mes)
$query_mes = "SELECT DATE_FORMAT(s.stoFechaRegis, '%Y-%m') as mes, COUNT(s.stoId) as cantidad FROM stock s 
              GROUP BY mes ORDER BY mes";
$result_mes = mysqli_query($con, $query_mes);
$meses = [];
$cantidades_mes = [];
while ($row2 = mysqli_fetch_assoc($result_mes)) {
    $meses[] = $row2['mes'];
    $cantidades_mes[] = $row2['cantidad'];
}

// Obtener datos para el gráfico de barras (Productos por Agotarse)
$query_agotarse = "SELECT p.proNombre, c.colNombre, t.talNombre, s.stoCantidad FROM stock s 
                   INNER JOIN producto p ON s.proId = p.proId 
                   INNER JOIN color c ON s.colId = c.colId 
                   INNER JOIN talla t ON s.talId = t.talId 
                   WHERE s.stoCantidad <= 10";
$result_agotarse = mysqli_query($con, $query_agotarse);
$productos_agotarse = [];
$cantidades_agotarse = [];

while ($row2 = mysqli_fetch_assoc($result_agotarse)) {
    $productos_agotarse[] = $row2['proNombre'];
    $cantidades_agotarse[] = $row2['stoCantidad'];
}
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reporte Stock</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Stock</a></li>
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
                            <button class="nav-link active" id="estado-tab" data-bs-toggle="tab" data-bs-target="#estado" type="button" role="tab" aria-controls="estado" aria-selected="true">Productos por Estado</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="mes-tab" data-bs-toggle="tab" data-bs-target="#mes" type="button" role="tab" aria-controls="mes" aria-selected="false">Tendencia de Stocks por Mes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="agotarse-tab" data-bs-toggle="tab" data-bs-target="#agotarse" type="button" role="tab" aria-controls="agotarse" aria-selected="false">Stock por Agotarse</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="estado" role="tabpanel" aria-labelledby="estado-tab">
                            <canvas id="barChart" class="mt-4"></canvas>
                        </div>
                        <div class="tab-pane fade" id="mes" role="tabpanel" aria-labelledby="mes-tab">
                            <canvas id="lineChart" class="mt-4"></canvas>
                        </div>
                        <div class="tab-pane fade" id="agotarse" role="tabpanel" aria-labelledby="agotarse-tab">
                            <canvas id="agotarseChart" class="mt-4"></canvas>
                        </div>
                    </div>
                </div>
            </div>

                    <div class="card mt-4" id="example">
                        <div class="card-header">
                          <h5 class="card-title mb-0">Lista de Stock <div class="badge-total">Total: <?php echo $total_registros; ?></div></h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="search" class="form-control" placeholder="Buscar por producto, color, talla o cantidad" value="<?php echo htmlspecialchars($search); ?>">
                                        <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select id="filterCategory" class="form-select">
                                        <option value="">Filtrar por Categoría</option>
                                        <?php
                                        $query = "SELECT DISTINCT c.catId, c.catNombre FROM categoria c INNER JOIN producto p ON c.catId = p.catId INNER JOIN stock s ON p.proId = s.proId";
                                        $result = mysqli_query($con, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['catId']}'" . ($filterCategory == $row['catId'] ? ' selected' : '') . ">{$row['catNombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <select id="filterState" class="form-select">
                                        <option value="">Filtrar por Estado</option>
                                        <option value="Disponible" <?php echo ($filterState === 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                                        <option value="No disponible" <?php echo ($filterState === 'No disponible') ? 'selected' : ''; ?>>No Disponible</option>
                                        <option value="En oferta" <?php echo ($filterState === 'En oferta') ? 'selected' : ''; ?>>En oferta</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <select id="order_dir" class="form-select">
                                        <option value="DESC" <?php echo ($order_dir == 'DESC') ? 'selected' : ''; ?>>Descendente</option>
                                        <option value="ASC" <?php echo ($order_dir == 'ASC') ? 'selected' : ''; ?>>Ascendente</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3 d-flex justify-content-end">
                                    <!-- Paginación -->
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&filterCategory=<?php echo htmlspecialchars($filterCategory); ?>&filterState=<?php echo htmlspecialchars($filterState); ?>&order_dir=<?php echo htmlspecialchars($order_dir); ?>#example"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </div>
                            </div>
                            <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                    <th>N</th>
                                        <th>Producto</th>
                                        <th>Estado</th>
                                        <th>Color</th>
                                        <th>Talla</th>
                                        <th> <div class="badge-dark"> Cantidad</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                             $query = "SELECT s.stoId, p.proNombre, e.estDisponible, c.colNombre, t.talNombre, s.stoCantidad,s.stoFechaRegis, p.proImg, p.proPrecio, cat.catNombre, m.marNombre, 
                             CASE WHEN o.stoId IS NOT NULL THEN 'En oferta' ELSE e.estDisponible END AS estado
                             FROM stock AS s
                             INNER JOIN producto AS p ON s.proId = p.proId
                             INNER JOIN estado AS e ON s.estId = e.estId
                             INNER JOIN color AS c ON s.colId = c.colId
                             INNER JOIN talla AS t ON s.talId = t.talId
                             INNER JOIN categoria AS cat ON p.catId = cat.catId
                            INNER JOIN marca as m on p.marId= m.marId
                             LEFT JOIN oferta o ON s.stoId = o.stoId
                             WHERE (p.proNombre LIKE ? OR c.colNombre LIKE ? OR t.talNombre LIKE ? OR s.stoCantidad LIKE ?)";
                   
                                    $params = [$search_param, $search_param, $search_param, $search_param];

                                    if ($filterCategory) {  
                                        $query .= " AND p.catId = ?";
                                        $params[] = $filterCategory;
                                    }

                                    if ($filterState !== '') {
                                        $query .= " AND (e.estDisponible = ? OR (o.stoId IS NOT NULL AND ? = 'En oferta'))";
                                        $params[] = $filterState;
                                        $params[] = $filterState;
                                    }

                                    $query .= " ORDER BY s.stoId $order_dir LIMIT $registros_por_pagina OFFSET $offset";
                                    $stmt = $con->prepare($query);
                                    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $numero_registro = $offset + 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $estado = $row['estado'];
                                        $estadoClass = 'badge-secondary';
                                        if ($estado == 'Disponible') {
                                            $estadoClass = 'badge-success';
                                        } elseif ($estado == 'No disponible') {
                                            $estadoClass = 'badge-danger';
                                        } elseif ($estado == 'En oferta') {
                                            $estadoClass = 'badge-warning';
                                        }
                                        echo "<tr id='stock-{$row['stoId']}'>
                                   <td>{$numero_registro}</td>
                                <td data-img='{$row['proImg']}' data-marca='{$row['marNombre']}' data-price='{$row['proPrecio']}' data-category='{$row['catNombre']}' 
                                class='product-name'>{$row['proNombre']}</td>
                                   <td><span class='{$estadoClass}'>{$estado}</span></td>
                                   <td>{$row['colNombre']}</td>
                                   <td>{$row['talNombre']}</td>
                                   <td>{$row['stoCantidad']}</td>
                                   <td>{$row['stoFechaRegis']}</td>
                              </tr>";
                                            $numero_registro++;
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
    <!-- tooltip hal hacer hover en la fila de la tabla -->
    <div id="stock-tooltip" style="position: absolute; display: none; background: white; border: 1px solid #ddd; padding: 10px; border-radius: 5px; z-index: 1000; text-align: center;">
        <img id="tooltip-img" src=" " alt="Producto" style="width: 200px; height: auto; margin-bottom: 10px;">
        <p id="tooltip-marca" style="margin: 0; font-weight: 500; font-style:italic"></p>
        <p id="tooltip-category" style="margin: 0; font-weight: bold;"></p>
        <p id="tooltip-price" style="margin: 0;"></p>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    function cambiarReporte() {
        const tipoReporte = document.getElementById('reporte_tipo').value;
        document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('show', 'active'));
        document.getElementById(tipoReporte).classList.add('show', 'active');
    }

    // Gráfico de Barras: Cantidad de Productos por Estado
    var ctxBar = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($estados); ?>,
            datasets: [{
                label: 'Cantidad de Stock por Estado',
                data: <?php echo json_encode($cantidades_estado); ?>,
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

    // Gráfico de Líneas: Tendencia de Stocks por Mes
    var ctxLine = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($meses); ?>,
            datasets: [{
                label: 'Tendencia de Stocks por Mes',
                data: <?php echo json_encode($cantidades_mes); ?>,
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

    // Gráfico de Barras: Productos por Agotarse (Cantidad <= 10)
    var ctxAgotarse = document.getElementById('agotarseChart').getContext('2d');
    var agotarseChart = new Chart(ctxAgotarse, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($productos_agotarse); ?>,
            datasets: [{
            label: 'Stock por Agotarse',
            data: <?php echo json_encode($cantidades_agotarse); ?>,
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
</script>
    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php";?>