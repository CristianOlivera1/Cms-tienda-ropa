<?php
include "../../header.php";
include "../../sidebar.php";

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda y filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM color WHERE colNombre LIKE ? OR colCodigoHex LIKE ?";
$stmt_total = $con->prepare($query_total);
$search_param = "%$search%";
$stmt_total->bind_param('ss', $search_param, $search_param);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reporte Colores</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Colores</a></li>
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
                                        <i class="fas fa-palette"></i> Reporte de color
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Grafico -->
                    </div>

                    <div class="card mt-4" id="example">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Colores <div class="badge-total">Total: <?php echo $total_registros ?> </div> </h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                        <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" id="search" class="form-control" placeholder="Buscar color" value="<?php echo htmlspecialchars($search); ?>">
                                        <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select id="order_dir" class="form-select">
                                        <option value="DESC" <?php echo ($order_dir == 'DESC') ? 'selected' : ''; ?>>Descendente</option>
                                        <option value="ASC" <?php echo ($order_dir == 'ASC') ? 'selected' : ''; ?>>Ascendente</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex justify-content-end">
                                <!-- Paginación -->
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&order_dir=<?php echo htmlspecialchars($order_dir); ?>#example"><?php echo $i; ?></a>
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
                                        <th>Nombre</th>
                                        <th>Color</th>
                                        <th>Fecha de registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM color WHERE colNombre LIKE ? OR colCodigoHex LIKE ? ORDER BY colId $order_dir LIMIT $registros_por_pagina OFFSET $offset";
                                    $stmt = $con->prepare($query);
                                    $stmt->bind_param('ss', $search_param, $search_param);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $numero_registro = $offset + 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr id='color-{$row['colId']}'>
                                                <td>{$numero_registro}</td>
                                                <td>{$row['colNombre']}</td>
                                                <td><div style='width: 20px; height: 20px; background-color: {$row['colCodigoHex']}; border-radius: 50%;'></div></td>
                                                <td>{$row['colFechaRegis']}</td>

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
    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php";?>