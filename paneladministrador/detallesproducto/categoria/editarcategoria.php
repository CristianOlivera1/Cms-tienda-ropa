<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $categoria_id = $_GET['id'];

    // Obtener los datos de la categoría
    $query = "SELECT * FROM categoria WHERE catId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $categoria = $result->fetch_assoc();

    if (!$categoria) {
        $error = 'Categoría no encontrada.';
    }
} else {
    $error = 'ID de categoría no proporcionado.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria_nombre = trim($_POST['catNombre']);

    if (empty($categoria_nombre)) {
        $error = 'El campo de categoría es obligatorio.';
    } else {
        $query = "UPDATE categoria SET catNombre = ? WHERE catId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $categoria_nombre, $categoria_id);
        if ($stmt->execute()) {
            $success = 'Categoría actualizada exitosamente.';
            // Actualizar los datos de la categoría
            $categoria['catNombre'] = $categoria_nombre;
        } else {
            $error = 'Error al actualizar la categoría.';
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
                        <h4 class="mb-sm-0">Editar Categoría</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Categorías</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#categoriaDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-tags"></i> Editar Categoría
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="categoriaDetails" role="tabpanel">
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
                                                    <label for="categoria_nombre" class="form-label">Nombre de la Categoría</label>
                                                    <input type="text" class="form-control" id="categoria_nombre" name="catNombre" value="<?php echo htmlspecialchars($categoria['catNombre']); ?>" required>
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