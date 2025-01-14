<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $marca_id = $_GET['id'];

    // Obtener los datos de la marca
    $query = "SELECT * FROM marca WHERE marId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $marca_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $marca = $result->fetch_assoc();

    if (!$marca) {
        $error = 'Marca no encontrada.';
    }
} else {
    $error = 'ID de marca no proporcionado.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marca_nombre = trim($_POST['marca_nombre']);
    $marca_img = $_FILES['marImg'];

    if (empty($marca_nombre)) {
        $error = 'El campo de marca es obligatorio.';
    } else {
        // Verificar si la marca ya existe (excluyendo la actual)
        $query_check = "SELECT * FROM marca WHERE marNombre = ? AND marId != ?";
        $stmt_check = $con->prepare($query_check);
        $stmt_check->bind_param('si', $marca_nombre, $marca_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error = 'La marca ya existe.';
        } else {
            // Verifica si se sube una nueva imagen
            if (!empty($marca_img['name'])) {
                // Manejar la carga de la imagen
                $target_dir = "../../recursos/uploads/marca/";
                $target_file = $target_dir . basename($marca_img["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Verificar si el archivo es una imagen real
                $check = getimagesize($marca_img["tmp_name"]);
                if ($check === false) {
                    $error = 'El archivo no es una imagen válida.';
                } elseif ($marca_img["size"] > 3000000) {
                    $error = 'El archivo es demasiado grande.';
                } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
                    $error = 'Solo se permiten archivos JPG, JPEG, PNG.';
                } else {
                    // Intentar mover el archivo
                    if (!move_uploaded_file($marca_img["tmp_name"], $target_file)) {
                        $error = 'Error al cargar la imagen.';
                    }
                }
            }

            if (empty($error)) {
                // Actualizar los datos de la marca
                $query = "UPDATE marca SET marNombre = ?" . (empty($marca_img['name']) ? "" : ", marImg = ?") . " WHERE marId = ?";
                $stmt = $con->prepare($query);
                if (empty($marca_img['name'])) {
                    $stmt->bind_param('si', $marca_nombre, $marca_id);
                } else {
                    $stmt->bind_param('ssi', $marca_nombre, $target_file, $marca_id);
                }
                if ($stmt->execute()) {
                    $success = 'Marca actualizada exitosamente.';
                    // Actualizar los datos de la marca
                    $marca['marNombre'] = $marca_nombre;
                    if (!empty($marca_img['name'])) {
                        $marca['marImg'] = $target_file; // Actualiza la imagen también
                    }
                } else {
                    $error = 'Error al actualizar la marca.';
                }
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
                        <h4 class="mb-sm-0">Editar Marca</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="gestionar-marca.php">Marcas</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#marcaDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-tags"></i> Editar Marca
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="marcaDetails" role="tabpanel">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible alert-outline fade show"><?php echo $error; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible alert-outline fade show"><?php echo $success; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="marca_nombre" class="form-label">Nombre de la Marca</label>
                                                    <input type="text" class="form-control" id="marca_nombre" name="marca_nombre" value="<?php echo htmlspecialchars($marca['marNombre']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="marca_img" class="form-label">Imagen</label>
                                                    <input type="file" class="form-control" id="marca_img" name="marImg">
                                                    <img src="<?php echo htmlspecialchars($marca['marImg']); ?>" alt="<?php echo htmlspecialchars($marca['marNombre']); ?>" style="width: 100px; height: 100px; margin-top: 10px;">
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