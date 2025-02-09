<?php
include "../../header.php";
include "../../sidebar.php";
$error = '';
$success = '';
$usuarioId = $_SESSION['admin_id'];

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
    $categoria_descripcion = trim($_POST['catDescripcion']);
    $categoria_detalle = trim($_POST['catDetalle']);
    $categoria_img = $_FILES['catImg'];

    if (empty($categoria_nombre) || empty($categoria_descripcion) || empty($categoria_detalle)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Verificar si el nombre de la categoría ya existe
        $query = "SELECT * FROM categoria WHERE catNombre = ? AND catId != ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $categoria_nombre, $categoria_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El nombre de la categoría ya existe.';
        } else {
            $target_file = $categoria['catImg'];
            $unique_name = $categoria['catImg'];
            if (!empty($categoria_img['name'])) {
                // Manejar la carga de la nueva imagen
                $target_dir = "../../recursos/uploads/categoria/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true); // Crear el directorio si no existe
                }
                $unique_name = uniqid() . '-' . basename($categoria_img["name"]);
                $target_file = $target_dir . $unique_name; // Renombrar el archivo para evitar conflictos
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Verificar si el archivo es una imagen real
                $check = getimagesize($categoria_img["tmp_name"]);
                if ($check === false) {
                    $error = 'El archivo no es una imagen.';
                } elseif ($categoria_img["size"] > 3000000) {
                    $error = 'El archivo es demasiado grande.';
                } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg','webp'])) {
                    $error = 'Solo se permiten archivos JPG, JPEG, PNG.';
                } else {
                    if (!move_uploaded_file($categoria_img["tmp_name"], $target_file)) {
                        $error = 'Error al cargar la imagen.';
                    }
                }
            }

            if (empty($error)) {
                $query = "UPDATE categoria SET catNombre = ?, catDescripcion = ?, catDetalle = ?, catImg = ? WHERE catId = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param('ssssi', $categoria_nombre, $categoria_descripcion, $categoria_detalle, $unique_name, $categoria_id);

                if ($stmt->execute()) {
                    $success = 'Categoría actualizada exitosamente.';
                    registrarActividad($con, $usuarioId,"Categoria", "Update", "Actualizó la categoría: Nombre - " . htmlspecialchars($categoria_nombre) . ", Descripción - " . htmlspecialchars($categoria_descripcion) . ", Detalle - " . htmlspecialchars($categoria_detalle) . ".");
                    // Actualizar los datos de la categoría
                    $categoria['catNombre'] = $categoria_nombre;
                    $categoria['catDescripcion'] = $categoria_descripcion;
                    $categoria['catDetalle'] = $categoria_detalle;
                    $categoria['catImg'] = $unique_name;
                } else {
                    $error = 'Error al actualizar la categoría.';
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
                        <h4 class="mb-sm-0">Editar Categoría</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="gestionar-categoria.php">Categorías</a></li>
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
                                        <div class="alert alert-danger alert-dismissible alert-outline fade show"><?php echo htmlspecialchars($error); ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible alert-outline fade show"><?php echo htmlspecialchars($success); ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_nombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="categoria_nombre" name="catNombre" value="<?php echo htmlspecialchars($categoria['catNombre']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_descripcion" class="form-label">Descripción</label>
                                                    <textarea class="form-control"id="categoria_descripcion" name="catDescripcion" required><?php echo htmlspecialchars($categoria['catDescripcion']); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_detalle" class="form-label">Detalle</label>
                                                    <textarea type="text" class="form-control"id="categoria_detalle" name="catDetalle" required><?php echo htmlspecialchars($categoria['catDetalle']); ?></textarea>

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_img" class="form-label">Imagen</label>
                                                    <input type="file" class="form-control" id="categoria_img" name="catImg">
                                                    <img src="../../recursos/uploads/categoria/<?php echo htmlspecialchars($categoria['catImg']); ?>" alt="<?php echo htmlspecialchars($categoria['catNombre']); ?>" style="width: 100px; height: 100px; margin-top: 10px;">
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
</div>

<?php include "../../footer.php";?>