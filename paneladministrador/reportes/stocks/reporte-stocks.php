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

            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i class="fas fa-box"></i> grafico de Stock
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- grafico de stocks -->
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
                             $query = "SELECT s.stoId, p.proNombre, e.estDisponible, c.colNombre, t.talNombre, s.stoCantidad, p.proImg, p.proPrecio, cat.catNombre, m.marNombre, 
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
    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php";?>