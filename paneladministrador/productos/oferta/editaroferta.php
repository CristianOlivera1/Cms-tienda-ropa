<?php
ob_start();
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

$oferta_id = (int)$_GET['id'];

// Obtener la oferta existente
$query = "SELECT * FROM oferta WHERE ofeId = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $oferta_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: list_ofertas.php'); // Redirigir si no se encuentra la oferta
    exit();
}

$oferta = $result->fetch_assoc();

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stoId = trim($_POST['stoId']);
    $ofePorcentaje = trim($_POST['ofePorcentaje']);
    $ofeTiempo = trim($_POST['ofeTiempo']);

    if (empty($stoId) || empty($ofePorcentaje) || empty($ofeTiempo)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Verificar si ya existe una oferta para este stock que no sea la actual
        $query_check = "SELECT COUNT(*) as count FROM oferta WHERE stoId = ? AND ofeId != ?";
        $stmt_check = $con->prepare($query_check);
        $stmt_check->bind_param('ii', $stoId, $oferta_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $count = $result_check->fetch_assoc()['count'];

        if ($count > 0) {
            $error = 'Este stock ya tiene una oferta registrada.';
        } else {
            // Actualizar oferta
            $query_update = "UPDATE oferta SET stoId = ?, ofePorcentaje = ?, ofeTiempo = ? WHERE ofeId = ?";
            $stmt_update = $con->prepare($query_update);
            $stmt_update->bind_param('idsi', $stoId, $ofePorcentaje, $ofeTiempo, $oferta_id);
            
            if ($stmt_update->execute()) {
                $success = 'Oferta actualizada exitosamente.';
            } else {
                $error = 'Error al actualizar la oferta.';
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
                        <h4 class="mb-sm-0">Editar Oferta</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-8">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Modificar Oferta</h5>
                        </div>

                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo $error; ?>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Éxito!</strong> <?php echo $success; ?>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            <?php endif; ?>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="stoId" class="form-label">Stock</label>
                                    <select class="form-select" id="stoId" name="stoId" required>
                                        <?php
                                        $query = "SELECT stoId FROM stock";
                                        $result = mysqli_query($con, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = ($row['stoId'] == $oferta['stoId']) ? 'selected' : '';
                                            echo "<option value='{$row['stoId']}' $selected>Stock ID: {$row['stoId']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="ofePorcentaje" class="form-label">Porcentaje de Descuento</label>
                                    <input type="number" class="form-control" id="ofePorcentaje" name="ofePorcentaje" required 
                                           value="<?php echo htmlspecialchars($oferta['ofePorcentaje']); ?>" min="0" max="100">
                                </div>
                                <div class="mb-3">
                                    <label for="ofeTiempo" class="form-label">Tiempo de Oferta (Fecha y Hora)</label>
                                    <input type="datetime-local" class="form-control" id="ofeTiempo" name="ofeTiempo" required 
                                           value="<?php echo date('Y-m-d\TH:i', strtotime($oferta['ofeTiempo'])); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Actualizar Oferta</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?php ob_end_flush(); ?>
<?php include "../../footer.php"; ?>