<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';
$usuarioId = $_SESSION['admin_id'];

if (isset($_GET['id'])) {
    $color_id = $_GET['id'];

    // Obtener los datos del color
    $query = "SELECT * FROM color WHERE colId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $color_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $color = $result->fetch_assoc();

    if (!$color) {
        $error = 'Color no encontrado.';
    }
} else {
    $error = 'ID de color no proporcionado.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $color_nombre = trim($_POST['color_nombre']);
    $color_hex = trim($_POST['color_hex']);

    if (empty($color_nombre)) {
        $error = 'El campo de nombre del color es obligatorio.';
    } else if (empty($color_hex)) {
        $error = 'El campo de código hexadecimal es obligatorio.';
    } else {
        // Verificar si el color ya existe
        $query = "SELECT * FROM color WHERE (colNombre = ? OR colCodigoHex = ?) AND colId != ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ssi', $color_nombre, $color_hex, $color_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El color ya se encuentra registrado, por favor seleccione otro color.';
        } else {
            $query = "UPDATE color SET colNombre = ?, colCodigoHex = ? WHERE colId = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('ssi', $color_nombre, $color_hex, $color_id);
            if ($stmt->execute()) {
                $success = 'Color actualizado exitosamente.';
                registrarActividad($con, $usuarioId, "Color", "Update", "Actualizó el color: Nombre - " . htmlspecialchars($color_nombre) . ", Código Hexadecimal - " . htmlspecialchars($color_hex) . ".");
                // Actualizar los datos del color
                $color['colNombre'] = $color_nombre;
                $color['colCodigoHex'] = $color_hex;
            } else {
                $error = 'Error al actualizar el color.';
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
                        <h4 class="mb-sm-0">Editar Color</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="gestionar-color.php">Colores</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#colorDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-palette"></i> Editar Color
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
                                                    <input type="text" class="form-control" id="color_nombre" name="color_nombre" value="<?php echo htmlspecialchars($color['colNombre']); ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="color_hex" class="form-label">Código Hexadecimal</label>
                                                    <input type="color" class="form-control picker-height" id="color_hex" name="color_hex" value="<?php echo htmlspecialchars($color['colCodigoHex']); ?>" required>
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