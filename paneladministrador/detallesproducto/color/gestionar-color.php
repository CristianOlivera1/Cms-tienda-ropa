<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $color_hex = trim($_POST['color_hex']);
    $color_nombre = trim($_POST['color_nombre']);

    if (empty($color_nombre)) {
        $error = 'El campo de nombre del color es obligatorio.';
    } else if (empty($color_hex)) {
        $error = 'El campo de código hexadecimal es obligatorio.';
    } else {
        // Verificar si el color ya existe
        $query = "SELECT * FROM color WHERE colNombre = ? OR colCodigoHex = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ss', $color_nombre, $color_hex);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El color ya se encuentra registrado, por favor seleccione otro color.';
        } else {
            $query = "INSERT INTO color (colNombre, colCodigoHex, colFechaRegis) VALUES (?, ?, NOW())";
            $stmt = $con->prepare($query);
            $stmt->bind_param('ss', $color_nombre, $color_hex);
            if ($stmt->execute()) {
                $success = 'Color registrado exitosamente.';
            } else {
                $error = 'Error al registrar el color.';
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
                        <h4 class="mb-sm-0">Gestionar Colores</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Colores</a></li>
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
                                        <i class="fas fa-palette"></i> Agregar Color
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="colorDetails" role="tabpanel">
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
                                                    <label for="color_nombre" class="form-label">Nombre del Color</label>
                                                    <input type="text" class="form-control" id="color_nombre" name="color_nombre" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="color_hex" class="form-label">Código Hexadecimal</label>
                                                    <input type="color" class="form-control picker-height" id="color_hex" name="color_hex" required>
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
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Color</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM color WHERE colNombre LIKE ? OR colCodigoHex LIKE ? ORDER BY colId $order_dir LIMIT $registros_por_pagina OFFSET $offset";
                                    $stmt = $con->prepare($query);
                                    $stmt->bind_param('ss', $search_param, $search_param);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr id='color-{$row['colId']}'>
                                                <td>{$row['colNombre']}</td>
                                                <td><div style='width: 20px; height: 20px; background-color: {$row['colCodigoHex']}; border-radius: 50%;'></div></td>
                                                <td >
                                                    <a href='editarcolor.php?id={$row['colId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1 aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                    <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteColor({$row['colId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
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
                    ¿Estás seguro de que deseas eliminar este color?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnColor">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php";?>