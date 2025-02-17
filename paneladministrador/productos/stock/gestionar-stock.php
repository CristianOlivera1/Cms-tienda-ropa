<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';
$usuarioId = $_SESSION['admin_id'];
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

$proId = '';
$estId = '';
$colId = '';
$talId = '';
$stoCantidad = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $proId = trim($_POST['proId']);
    $estId = trim($_POST['estId']);
    $colId = trim($_POST['colId']);
    $talId = trim($_POST['talId']);
    $stoCantidad = trim($_POST['stoCantidad']);

    if (empty($proId) || empty($estId) || empty($colId) || empty($talId) || empty($stoCantidad)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Verificar si el stock ya existe
        $query = "SELECT * FROM stock WHERE proId = ? AND estId = ? AND colId = ? AND talId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('iiii', $proId, $estId, $colId, $talId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El stock del producto ya se encuentra registrado, por favor inserte otro stock para otro producto.';
        } else {
            $query = "INSERT INTO stock (proId, estId, colId, talId, stoCantidad, stoFechaRegis) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $con->prepare($query);
            $stmt->bind_param('iiiii', $proId, $estId, $colId, $talId, $stoCantidad);
            if ($stmt->execute()) {
                registrarActividad($con, $usuarioId, "Stock", "Insert", "Registró el stock: Producto ID - " . htmlspecialchars($proId) . ", Estado ID - " . htmlspecialchars($estId) . ", Color ID - " . htmlspecialchars($colId) . ", Talla ID - " . htmlspecialchars($talId) . ", Cantidad - " . htmlspecialchars($stoCantidad) . ".");
                $success = 'Stock registrado exitosamente.';
                $proId = '';
                $estId = '';
                $colId = '';
                $talId = '';
                $stoCantidad = '';
            } else {
                $error = 'Error al registrar el stock.';
            }
        }
    }
}
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Gestionar Stock</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Stock</a></li>
                                <li class="breadcrumb-item active">Gestionar</li>
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
                                        <i class="fas fa-box"></i> Agregar Stock
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="stockDetails" role="tabpanel">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible alert-outline fade show"><?php echo $error; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible alert-outline fade show"><?php echo $success; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <form method="POST" action="">
                                        <div class="row">
                                            <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="proId" class="form-label">Producto</label>
                                                <select class="form-select" id="proId" name="proId" required onchange="filtrarTallas(this.value); showProductImage(this.value)">
                                                    <option value="" selected>Seleccione producto</option>
                                                    <?php
                                                    $query = "SELECT proId, proNombre, proImg FROM producto order by proNombre asc";
                                                    $result = mysqli_query($con, $query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='{$row['proId']}'  data-img-pro='{$row['proImg']}'>{$row['proNombre']} </option>";
                                                    }
                                                    ?>
                                               </select>
                                            </div>
                                                <div class="mb-3">
                                                    <label for="proImg" class="form-label">Imagen del Producto</label>
                                                    <div id="productImageContainer">
                                                        <?php
                                                        if ($proId) {
                                                            $query = "SELECT proImg FROM producto WHERE proId = ?";
                                                            $stmt = $con->prepare($query);
                                                            $stmt->bind_param('i', $proId);
                                                            $stmt->execute();
                                                            $result = $stmt->get_result();
                                                            if ($row = $result->fetch_assoc()) {
                                                                echo "<img src='../../recursos/uploads/producto/{$row['proImg']}' alt='Imagen del Producto' class='img-fluid' id='productImage' style='width: 50%; height: auto;'>";
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                            <div class="mb-3">
                                                    <label for="estId" class="form-label">Estado</label>
                                                    <select class="form-select" id="estId" name="estId" required>
                                                        <?php
                                                        $query = "SELECT estId, estDisponible FROM estado";
                                                        $result = mysqli_query($con, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $selected = ($row['estId'] == $estId) ? 'selected' : '';
                                                            echo "<option value='{$row['estId']}' $selected>{$row['estDisponible']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="colId" class="form-label">Color</label>
                                                    <div class="d-flex align-items-center">
                                                        <select class="form-select me-2" id="colId" name="colId" required onchange="updateColorPreview(this)">
                                                        <option value="" selected>Seleccione color</option>
                                                            <?php
                                                            $query = "SELECT colId, colNombre, colCodigoHex FROM color order by colNombre asc";
                                                            $result = mysqli_query($con, $query);
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $selected = ($row['colId'] == $colId) ? 'selected' : '';
                                                                echo "<option value='{$row['colId']}' data-hex='{$row['colCodigoHex']}' $selected>{$row['colNombre']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <span id="colorPreview" style="background-color: <?php echo $colId ? $row['colCodigoHex'] : '#ffffff'; ?>; width: 35px; height: 35px; display: inline-block; border: 1px solid #000;   border-radius: 6px;"></span>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="talId" class="form-label">Talla</label>
                                                    <select class="form-select" id="talId" name="talId" required>
                                                        <option value="" selected>Seleccione talla</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="stoCantidad" class="form-label">Cantidad</label>
                                                    <div class="input-group">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="decrement()">-</button>
                                                        <input type="number" class="form-control" id="stoCantidad" name="stoCantidad" required min="0" step="1" value="<?php echo htmlspecialchars($stoCantidad) ?: '0'; ?>">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="increment()">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                                        <th>Acción</th>
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
                                   <td>
                                      <a href='editarstock.php?id={$row['stoId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1' aria-label='Editar' title='Editar'>
                                          <i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i>
                                      </a>
                                      <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteStock({$row['stoId']})' aria-label='Eliminar' title='Eliminar'>
                                          <i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i>
                                      </a>
                                   </td>
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


    <!-- Modal de confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este stock?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnStock">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php";?>