<?php
include "../../header.php";
include "../../sidebar.php";

// Variables de búsqueda y paginación
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$filter_marca = isset($_GET['marca']) ? trim($_GET['marca']) : '';
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = 10;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros con filtros
$query_total = "SELECT COUNT(*) as total FROM producto WHERE proNombre LIKE ? AND (catId = ? OR ? = '') AND (marId = ? OR ? = '')";
$stmt_total = $con->prepare($query_total);
$search_param = "%$search%";
$stmt_total->bind_param('sssss', $search_param, $filter_categoria, $filter_categoria, $filter_marca, $filter_marca);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener productos con filtros y paginación
$query = "SELECT p.*, c.catNombre, m.marNombre FROM producto p
          JOIN categoria c ON p.catId = c.catId
          JOIN marca m ON p.marId = m.marId
          WHERE p.proNombre LIKE ? AND (p.catId = ? OR ? = '') AND (p.marId = ? OR ? = '')
          ORDER BY p.proId DESC
          LIMIT ? OFFSET ?";
$stmt = $con->prepare($query);
$stmt->bind_param('ssssiii', $search_param, $filter_categoria, $filter_categoria, $filter_marca, $filter_marca, $registros_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

//reporte
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
// Obtener datos para el gráfico de pastel (Distribución de Productos por Marca)
$query_pie = "SELECT m.marNombre, COUNT(p.proId) as cantidad FROM marca m 
              LEFT JOIN producto p ON m.marId = p.marId 
              GROUP BY m.marNombre";
$result_pie = mysqli_query($con, $query_pie);
$marcas = [];
$cantidades_pie = [];
while ($row = mysqli_fetch_assoc($result_pie)) {
    $marcas[] = $row['marNombre'];
    $cantidades_pie[] = $row['cantidad'];
}

// Obtener datos para el gráfico de líneas (Tendencia de Nuevos Productos por Mes)
$query_line = "SELECT DATE_FORMAT(proFechaRegistro, '%Y-%m') as mes, COUNT(proId) as cantidad FROM producto 
               GROUP BY mes ORDER BY mes";
$result_line = mysqli_query($con, $query_line);
$meses = [];
$cantidades_line = [];
while ($row = mysqli_fetch_assoc($result_line)) {
    $meses[] = $row['mes'];
    $cantidades_line[] = $row['cantidad'];
}

?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reporte de Productos</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Productos</a></li>
                                <li class="breadcrumb-item active">Reporte</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-9">
                    <!-- Pestañas para los gráficos -->
                    <div class="row" style="background-color: white;">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="bar-tab" data-bs-toggle="tab" data-bs-target="#bar" type="button" role="tab" aria-controls="bar" aria-selected="true">Productos por Categoría</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pie-tab" data-bs-toggle="tab" data-bs-target="#pie" type="button" role="tab" aria-controls="pie" aria-selected="false">Productos por Marca</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="line-tab" data-bs-toggle="tab" data-bs-target="#line" type="button" role="tab" aria-controls="line" aria-selected="false">Productos por Mes</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="no-stock-tab" data-bs-toggle="tab" data-bs-target="#no-stock" type="button" role="tab">Productos Sin Registro en Stock</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="bar" role="tabpanel" aria-labelledby="bar-tab">
                                    <canvas id="barChart" class="mt-4"></canvas>
                                </div>

                                <div class="tab-pane fade justify-content-center" id="pie" role="tabpanel" aria-labelledby="pie-tab" style="height: 600px;">
                                    <canvas id="pieChart" class="mt-4" style="height: 100%;"></canvas>
                                </div>

                                <div class="tab-pane fade" id="line" role="tabpanel" aria-labelledby="line-tab">
                                    <canvas id="lineChart" class="mt-4"></canvas>
                                </div>

                                <div class="tab-pane fade" id="no-stock" role="tabpanel">
                                    <div class="container mt-4">
                                        <h4 class="mb-4">Productos Sin Registro en Stock</h4>
                                        <div class="row">
                                            <?php
                                            $productos_por_pagina = 9;
                                            $pagina_actual_no_stock = isset($_GET['pagina_no_stock']) ? (int)$_GET['pagina_no_stock'] : 1;
                                            $offset_no_stock = ($pagina_actual_no_stock - 1) * $productos_por_pagina;

                                            $query_total_no_stock = "SELECT COUNT(*) as total FROM producto p
                                                                     LEFT JOIN stock s ON p.proId = s.proId
                                                                     WHERE s.proId IS NULL";
                                            $result_total_no_stock = mysqli_query($con, $query_total_no_stock);
                                            $total_productos_no_stock = mysqli_fetch_assoc($result_total_no_stock)['total'];
                                            $total_paginas_no_stock = ceil($total_productos_no_stock / $productos_por_pagina);

                                            $query_no_stock = "SELECT p.proId, p.proNombre, p.proImg, m.marNombre
                                                               FROM producto p
                                                               LEFT JOIN stock s ON p.proId = s.proId
                                                               INNER JOIN marca m ON m.marId = p.marId
                                                               WHERE s.proId IS NULL
                                                               LIMIT $productos_por_pagina OFFSET $offset_no_stock";

                                            $result_no_stock = mysqli_query($con, $query_no_stock);

                                            while ($producto = mysqli_fetch_assoc($result_no_stock)) {
                                                echo "
                            <div class='col-md-4'>
                                <div class='card mb-4'>
                                    <div class='d-flex align-items-center p-2'>
                                        <img src='/paneladministrador/recursos/uploads/producto/{$producto['proImg']}' class='rounded-circle me-3' alt='{$producto['proNombre']}' style='width: 50px; height: 50px;'>
                                        <div>
                                            <p class='card-title mb-1'>{$producto['marNombre']} - {$producto['proNombre']}</p>
                                            <p class='text-warning mb-0'><strong>No registrado en stock</strong></p>
                                        </div>
                                        <a href='http://localhost:3000/paneladministrador/productos/stock/gestionar-stock.php' class='btn btn-primary btn-sm' style='margin-top: auto;'>Registrar</a>
                                    </div>
                                </div>
                            </div>";
                                            }
                                            ?>
                                        </div>
                                        <!-- Paginación -->
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-center">
                                                <?php for ($i = 1; $i <= $total_paginas_no_stock; $i++): ?>
                                                    <li class="page-item <?php echo ($i == $pagina_actual_no_stock) ? 'active' : ''; ?>">
                                                        <a class="page-link" href="#" onclick="loadNoStockPage(<?php echo $i; ?>)"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Productos<div class="badge-total">Total: <?php echo $total_registros; ?></div>
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="" id="filterForm">
                                <div class="row mb-3">

                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="text" id="search" class="form-control" placeholder="Buscar producto" value="<?php echo htmlspecialchars($search); ?>" onkeyup="if(event.keyCode == 13) window.location.href='?search='+this.value">
                                            <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="categoria" class="form-select me-2" onchange="this.form.submit()">
                                            <option value="">Todas las categorías</option>
                                            <?php
                                            $query_categorias = "SELECT * FROM categoria";
                                            $result_categorias = mysqli_query($con, $query_categorias);
                                            while ($row_categoria = mysqli_fetch_assoc($result_categorias)) {
                                                $selected = ($filter_categoria == $row_categoria['catId']) ? 'selected' : '';
                                                echo "<option value='{$row_categoria['catId']}' $selected>{$row_categoria['catNombre']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="marca" class="form-select me-2" onchange="this.form.submit()">
                                            <option value="">Todas las marcas</option>
                                            <?php
                                            $query_marcas = "SELECT * FROM marca";
                                            $result_marcas = mysqli_query($con, $query_marcas);
                                            while ($row_marca = mysqli_fetch_assoc($result_marcas)) {
                                                $selected = ($filter_marca == $row_marca['marId']) ? 'selected' : '';
                                                echo "<option value='{$row_marca['marId']}' $selected>{$row_marca['marNombre']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex justify-content-end">
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
                    <!-- Tabla de productos -->
                    <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>Nombre del Producto</th>
                                <th>Imagen Principal</th>
                                <th>Precio</th>
                                <th>Fecha de registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $numero = $offset + 1;
                            while ($row = $result->fetch_assoc()) {
                                $image_path1 = "../../recursos/uploads/producto/" . htmlspecialchars($row['proImg']);
                                echo "<tr id='producto-{$row['proId']}' ondblclick='openProductModal({$row['proId']}, \"{$row['proNombre']}\", \"{$row['proDescripcion']}\", \"{$row['proImg']}\", \"{$row['proImg2']}\", {$row['proPrecio']}, \"{$row['catNombre']}\", \"{$row['marNombre']}\")'>
                                                <td>{$numero}</td>
                                                <td>" . htmlspecialchars($row['proNombre']) . "</td>
                                                <td><img src='" . $image_path1 . "' alt='" . htmlspecialchars($row['proNombre']) . "' style='width: 50px; height: 50px;'></td>
                                                <td>S/. " . htmlspecialchars($row['proPrecio']) . "</td>
                                                <td>{$row['proFechaRegistro']}</td>

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
    // Gráfico de Pastel: Distribución de Productos por Marca
    var ctxPie = document.getElementById('pieChart').getContext('2d');
    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($marcas); ?>,
            datasets: [{
                label: 'Distribución de Productos por Marca',
                data: <?php echo json_encode($cantidades_pie); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(55, 58, 59, 0.2)',
                    'rgba(255, 86, 86, 0.36)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(0, 153, 255, 0.46)',
                    'rgba(139, 255, 86, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(31, 29, 29, 0.48)',
                    'rgb(255, 86, 86)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgb(0, 153, 255)',
                    'rgb(139, 255, 86)',
                ],
                borderWidth: 1
            }]
        }
    });

    // Gráfico de Líneas: Tendencia de Nuevos Productos por Mes
    var ctxLine = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($meses); ?>,
            datasets: [{
                label: 'Nuevos Productos',
                data: <?php echo json_encode($cantidades_line); ?>,
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

    function loadNoStockPage(page) {
        $.ajax({
            url: 'reporte-productos.php',
            type: 'GET',
            data: {
                pagina_no_stock: page
            },
            success: function(response) {
                var newContent = $(response).find('#no-stock .container').html();
                $('#no-stock .container').html(newContent);
            }
        });
    }
</script>
<script src="../../recursos/js/script.js"></script>
<?php include "../../footer.php"; ?>