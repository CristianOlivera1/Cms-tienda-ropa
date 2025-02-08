<?php
include "../../header.php";
include "../../sidebar.php";

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM talla WHERE talNombre LIKE ?";
$search_param = "%$search%";
$stmt_total = $con->prepare($query_total);
$stmt_total->bind_param('s', $search_param);
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
                        <h4 class="mb-sm-0">Reporte Tallas</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tallas</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tallaDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-ruler"></i> Grafico de tallas
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- reporte de tallas -->
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Tallas<div class="badge-total">Total: <?php echo $total_registros; ?></div></h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="search" class="form-control" placeholder="Buscar talla" value="<?php echo htmlspecialchars($search); ?>">
                                        <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
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
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                    <th>N</th>
                                    <th>Nombre de la Talla</th>
                                    <th>Fecha de registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM talla WHERE talNombre LIKE ? ORDER BY talId DESC LIMIT ? OFFSET ?";
                                    $stmt = $con->prepare($query);
                                    $stmt->bind_param('sii', $search_param, $registros_por_pagina, $offset);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $numeracion=$offset+1;
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr id='talla-{$row['talId']}'>
                                                <td>{$numeracion}</td>
                                                <td>{$row['talNombre']}</td>
                                                <td>{$row['talFechaRegis']}</td>
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
        <script src="../../recursos/js/script.js"></script>
    </div>
<?php include "../../footer.php"; ?>