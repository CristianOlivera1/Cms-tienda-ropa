<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $stock_id = $_GET['id'];

    // Obtener los datos del stock
    $query = "SELECT * FROM stock WHERE stoId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $stock_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stock = $result->fetch_assoc();

    if (!$stock) {
        $error = 'Stock no encontrado.';
    }
} else {
    $error = 'ID de stock no proporcionado.';
}

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
        $query = "SELECT * FROM stock WHERE proId = ? AND estId = ? AND colId = ? AND talId = ? AND stoId != ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('iiiii', $proId, $estId, $colId, $talId, $stock_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El stock del producto ya se encuentra registrado, por favor inserte otro stock para otro producto.';
        } else {
            $query = "UPDATE stock SET proId = ?, estId = ?, colId = ?, talId = ?, stoCantidad = ? WHERE stoId = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('iiiiii', $proId, $estId, $colId, $talId, $stoCantidad, $stock_id);
            if ($stmt->execute()) {
                $success = 'Stock actualizado exitosamente.';
                // Actualizar los datos del stock
                $stock['proId'] = $proId;
                $stock['estId'] = $estId;
                $stock['colId'] = $colId;
                $stock['talId'] = $talId;
                $stock['stoCantidad'] = $stoCantidad;
            } else {
                $error = 'Error al actualizar el stock.';
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
                        <h4 class="mb-sm-0">Editar Stock</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="gestionar-stock.php">Stock</a></li>
                                <li class="breadcrumb-item active">Editar</li>
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
                                        <i class="fas fa-box"></i> Editar Stock
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
                                                            $selected = ($row['proId'] == $stock['proId']) ? 'selected' : '';
                                                            echo "<option value='{$row['proId']}' {$selected}>{$row['proNombre']}</option>";
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
                                                            $selected = ($row['estId'] == $stock['estId']) ? 'selected' : '';
                                                            echo "<option value='{$row['estId']}' {$selected}>{$estado}</option>";
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
                                                            $selected = ($row['colId'] == $stock['colId']) ? 'selected' : '';
                                                            echo "<option value='{$row['colId']}' {$selected}>{$row['colNombre']}</option>";
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
                                                            $selected = ($row['talId'] == $stock['talId']) ? 'selected' : '';
                                                            echo "<option value='{$row['talId']}' {$selected}>{$row['talNombre']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="stoCantidad" class="form-label">Cantidad</label>
                                                    <div class="input-group">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="decrement()">-</button>
                                                        <input type="number" class="form-control" id="stoCantidad" name="stoCantidad" required value="<?php echo htmlspecialchars($stock['stoCantidad']); ?>">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="increment()">+</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../recursos/js/script.js"></script>

</div>

<?php include "../../footer.php";?>