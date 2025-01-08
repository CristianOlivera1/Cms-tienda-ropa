<?php
include "../../header.php";
include "../../sidebar.php";
include_once ("../../coneccionbd.php");

$error = '';
$success = '';

// Manejar el registro de tallas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tallaNombre = trim($_POST['talla']);

    if (empty($tallaNombre)) {
        $error = 'El campo de talla es obligatorio.';
    } else {
        $accion = $_POST['accion'];
        if ($accion === 'guardar') {
            // Guardar nueva talla
            $stmt = $con->prepare("INSERT INTO talla (talNombre, talFechaRegis) VALUES (?, NOW())");
            $stmt->bind_param("s", $tallaNombre);
            if ($stmt->execute()) {
                $success = "Talla registrada correctamente.";
            } else {
                $error = "Error al registrar la talla: " . $stmt->error;
            }
        } elseif ($accion === 'editar') {
            // Editar talla existente
            $tallaId = $_POST['talla_id']; // Obtener el ID de la talla a editar
            $stmt = $con->prepare("UPDATE talla SET talNombre = ? WHERE talId = ?");
            $stmt->bind_param("si", $tallaNombre, $tallaId);
            if ($stmt->execute()) {
                $success = "Talla actualizada correctamente.";
            } else {
                $error = "Error al actualizar la talla: " . $stmt->error;
            }
        }
    }
}

// Manejar la eliminación de tallas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $tallaId = $_POST['talla_id'];
    $stmt = $con->prepare("DELETE FROM talla WHERE talId = ?");
    $stmt->bind_param("i", $tallaId);
    if ($stmt->execute()) {
        $success = "Talla eliminada correctamente.";
    } else {
        $error = "Error al eliminar la talla: " . $stmt->error;
    }
}

// Consultar tallas existentes
$tallas = [];
$stmt = $con->query("SELECT * FROM talla");
if ($stmt) {
    $tallas = $stmt->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="main-content">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .text-center {
            text-align: center;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <!-- Título de la página -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between text-center">
                        <h4 class="mb-sm-0">Gestión de Tallas de Ropa</h4>
                    </div>
                </div>
            </div>
            <!-- Fin del título de la página -->

            <!-- Mensajes de éxito o error -->
            <div class="row">
                <div class="col-12 text-center">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulario para registrar o editar tallas -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Registrar Talla</h5>
                        </div>
                        <div class="card-body">
                            <form id="form-talla" method="POST">
                                <div class="mb-3">
                                    <label for="talla" class="form-label">Talla</label>
                                    <input type="text" name="talla" class="form-control" id="talla" required>
                                    <input type="hidden" name="talla_id" id="talla_id"> <!-- Campo oculto para ID de talla -->
                                </div>
                                <input type="hidden" name="accion" value="guardar">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <button type="button" class="btn btn-secondary" id="btn-limpiar">Limpiar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla para visualizar tallas -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Tallas Registradas</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Talla</th>
                                        <th class="text-center">Fecha de Registro</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-tallas">
                                    <?php foreach ($tallas as $talla): ?>
                                        <tr data-id="<?php echo $talla['talId']; ?>">
                                            <td class="text-center"><?php echo htmlspecialchars($talla['talNombre']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($talla['talFechaRegis']); ?></td>
                                            <td class="text-center">
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="javascript:void(0);" class="dropdown-item" onclick="editarTalla('<?php echo htmlspecialchars($talla['talId']); ?>', '<?php echo htmlspecialchars($talla['talNombre']); ?>')"><i class="bi bi-pencil-square"></i> Editar</a></li>
                                                        <li><a href="javascript:void(0);" class="dropdown-item" onclick="confirmarEliminarTalla(<?php echo $talla['talId']; ?>)"><i class="bi bi-trash"></i> Eliminar</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta talla?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/script.js"></script>
    </div>

<?php include "../../footer.php"; ?>
