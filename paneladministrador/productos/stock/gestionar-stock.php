<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM stock";
$result_total = mysqli_query($con, $query_total);
$total_registros = mysqli_fetch_assoc($result_total)['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

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
                $success = 'Stock registrado exitosamente.';
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
                                                    <select class="form-select" id="proId" name="proId" required>
                                                        <?php
                                                        $query = "SELECT proId, proNombre FROM producto";
                                                        $result = mysqli_query($con, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['proId']}'>{$row['proNombre']}</option>";
                                                        }
                                                        ?>
                                                    </select>
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
                                                            $estado = $row['estDisponible'] ? 'Disponible' : 'No Disponible';
                                                            echo "<option value='{$row['estId']}'>{$estado}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="colId" class="form-label">Color</label>
                                                    <select class="form-select" id="colId" name="colId" required>
                                                        <?php
                                                        $query = "SELECT colId, colNombre FROM color";
                                                        $result = mysqli_query($con, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['colId']}'>{$row['colNombre']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="talId" class="form-label">Talla</label>
                                                    <select class="form-select" id="talId" name="talId" required>
                                                        <?php
                                                        $query = "SELECT talId, talNombre FROM talla";
                                                        $result = mysqli_query($con, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['talId']}'>{$row['talNombre']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="stoCantidad" class="form-label">Cantidad</label>
                                                    <input type="number" class="form-control" id="stoCantidad" name="stoCantidad" required placeholder="Agregue una cantidad">
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

                    <div class="card mt-4">
                        <div class="card-header">
                          <h5 class="card-title mb-0">Lista de Stock <div class="badge-total">Total: <?php echo $total_registros; ?></div></h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" id="search" class="form-control" placeholder="Buscar por producto, color, talla o cantidad">
                                        <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select id="filterCategory" class="form-select">
                                        <option value="">Filtrar por Categoría</option>
                                        <?php
                                        $query = "SELECT DISTINCT c.catId, c.catNombre FROM categoria c INNER JOIN producto p ON c.catId = p.catId INNER JOIN stock s ON p.proId = s.proId";
                                        $result = mysqli_query($con, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['catId']}'>{$row['catNombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="filterState" class="form-select">
                                        <option value="">Filtrar por Estado</option>
                                        <option value="1">Disponible</option>
                                        <option value="0">No Disponible</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                <!-- Paginación -->
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <?php if ($pagina_actual > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>#example" tabindex="-1">Anterior</a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1">Anterior</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?pagina=<?php echo $i; ?>#example"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($pagina_actual < $total_paginas): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>#example">Siguiente</a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#">Siguiente</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                                </div>
                            </div>
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
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
                                    $query = "SELECT s.stoId, p.proNombre, e.estDisponible, c.colNombre, t.talNombre, s.stoCantidad, p.catId 
                                              FROM stock AS s
                                              INNER JOIN producto AS p ON s.proId = p.proId
                                              INNER JOIN estado AS e ON s.estId = e.estId
                                              INNER JOIN color AS c ON s.colId = c.colId
                                              INNER JOIN talla AS t ON s.talId = t.talId
                                              ORDER BY s.stoId DESC
                                              LIMIT $registros_por_pagina OFFSET $offset";
                                    $result = mysqli_query($con, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $estado = $row['estDisponible'] ? 'Disponible' : 'No Disponible';
                                        $estadoClass = $row['estDisponible'] ? 'badge-success' : 'badge-danger';
                                        echo "<tr id='stock-{$row['stoId']}' data-category='{$row['catId']}'>
                                                <td>{$row['proNombre']}</td>
                                                <td><span class='{$estadoClass}'>{$estado}</span></td>
                                                <td>{$row['colNombre']}</td>
                                                <td>{$row['talNombre']}</td>
                                                <td>{$row['stoCantidad']}</td>
                                                <td>
                                                   <a href='editarstock.php?id={$row['stoId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1' aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                    <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteStock({$row['stoId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                </td>
                                            </tr>";
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