<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $talla_id = $_GET['id'];

    // Obtener los datos de la talla
    $query = "SELECT * FROM talla WHERE talId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $talla_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $talla = $result->fetch_assoc();

    if (!$talla) {
        $error = 'Talla no encontrada.';
    }
} else {
    $error = 'ID de talla no proporcionado.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $talla_nombre = trim($_POST['talla_nombre']);

    if (empty($talla_nombre)) {
        $error = 'El campo de talla es obligatorio.';
    } else {
        $query = "UPDATE talla SET talNombre = ? WHERE talId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $talla_nombre, $talla_id);
        if ($stmt->execute()) {
            $success = 'Talla actualizada exitosamente.';
            // Actualizar los datos de la talla
            $talla['talNombre'] = $talla_nombre;
        } else {
            $error = 'Error al actualizar la talla.';
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
                        <h4 class="mb-sm-0">Editar Talla</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tallas</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tallaDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-ruler"></i> Editar Talla
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tallaDetails" role="tabpanel">
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
                                                    <label for="talla_nombre" class="form-label">Nombre de la Talla</label>
                                                    <input type="text" class="form-control" id="talla_nombre" name="talla_nombre" value="<?php echo htmlspecialchars($talla['talNombre']); ?>" required>
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

<?php
include "../../footer.php";
?>