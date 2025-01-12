<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#stockDetails" role="tab" aria-selected="false">
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
                                                        $query = "SELECT proId, proDescripcion FROM producto";
                                                        $result = mysqli_query($con, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='{$row['proId']}'>{$row['proDescripcion']}</option>";
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
                                                    <input type="number" class="form-control" id="stoCantidad" name="stoCantidad" required>
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
                            <h5 class="card-title mb-0">Lista de Stock</h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Estado</th>
                                        <th>Color</th>
                                        <th>Talla</th>
                                        <th>Cantidad</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT s.stoId, p.proDescripcion, e.estDisponible, c.colNombre, t.talNombre, s.stoCantidad 
                                              FROM stock AS s
                                              INNER JOIN producto AS p ON s.proId = p.proId
                                              INNER JOIN estado AS e ON s.estId = e.estId
                                              INNER JOIN color AS c ON s.colId = c.colId
                                              INNER JOIN talla AS t ON s.talId = t.talId
                                              ORDER BY s.stoId DESC";
                                    $result = mysqli_query($con, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $estado = $row['estDisponible'] ? 'Disponible' : 'No Disponible';
                                        echo "<tr id='stock-{$row['stoId']}'>
                                                <td>{$row['proDescripcion']}</td>
                                                <td>{$estado}</td>
                                                <td>{$row['colNombre']}</td>
                                                <td>{$row['talNombre']}</td>
                                                <td>{$row['stoCantidad']}</td>
                                                <td>
                                                   <a href='editarstock.php?id={$row['stoId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1 aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
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