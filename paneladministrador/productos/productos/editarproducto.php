<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Obtener los datos del producto
    $query = "SELECT * FROM producto WHERE proId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if (!$producto) {
        $error = 'Producto no encontrado.';
    }
} else {
    $error = 'ID de producto no proporcionado.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_nombre = trim($_POST['proNombre']);
    $producto_descripcion = trim($_POST['proDescripcion']);
    $producto_precio = isset($_POST['proPrecio']) ? (float)$_POST['proPrecio'] : 0;
    $categoria_id = isset($_POST['catId']) ? (int)$_POST['catId'] : 0;
    $marca_id = isset($_POST['marId']) ? (int)$_POST['marId'] : 0;
    $producto_img = $_FILES['proImg'];
    $producto_img2 = $_FILES['proImg2'];

    // Validación de campos
    if (empty($producto_nombre) || empty($producto_descripcion) || $producto_precio < 0 || empty($categoria_id) || empty($marca_id)) {
        $error = 'Todos los campos son obligatorios y deben ser válidos.';
    } else {
        // Verificar si el nombre del producto ya existe
        $query_check = "SELECT COUNT(*) as count FROM producto WHERE proNombre = ? AND proId != ?";
        $stmt_check = $con->prepare($query_check);
        $stmt_check->bind_param('si', $producto_nombre, $producto_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $count = $result_check->fetch_assoc()['count'];

        if ($count > 0) {
            $error = 'Ya existe un producto con este nombre.';
        } else {
            // Manejar la carga de imágenes
            $target_dir = "../../recursos/uploads/producto/";
            $target_file = $producto['proImg'];
            $target_file2 = $producto['proImg2'];

            // Manejar imagen principal
            if (!empty($producto_img['name'])) {
                $target_file = $target_dir . basename($producto_img["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Validaciones de imagen
                if (getimagesize($producto_img["tmp_name"]) === false) {
                    $error = 'El archivo de la imagen principal no es una imagen.';
                } elseif ($producto_img["size"] > 3000000) {
                    $error = 'La imagen principal es demasiado grande.';
                } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'webp'])) {
                    $error = 'Solo se permiten archivos JPG, JPEG, PNG para la imagen principal.';
                } else {
                    move_uploaded_file($producto_img["tmp_name"], $target_file);
                }
            }

            // Manejar imagen secundaria
            if (!empty($producto_img2['name'])) {
                $target_file2 = $target_dir . basename($producto_img2["name"]);
                $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

                // Validaciones de imagen
                if (getimagesize($producto_img2["tmp_name"]) === false) {
                    $error = 'El archivo de la imagen secundaria no es una imagen.';
                } elseif ($producto_img2["size"] > 3000000) {
                    $error = 'La imagen secundaria es demasiado grande.';
                } elseif (!in_array($imageFileType2, ['jpg', 'png', 'jpeg'])) {
                    $error = 'Solo se permiten archivos JPG, JPEG, PNG para la imagen secundaria.';
                } else {
                    move_uploaded_file($producto_img2["tmp_name"], $target_file2);
                }
            }

            if (empty($error)) {
                // Actualizar en la base de datos
                $query_update = "UPDATE producto SET catId = ?, marId = ?, proNombre = ?, proDescripcion = ?, proImg = ?, proImg2 = ?, proPrecio = ? WHERE proId = ?";
                $stmt_update = $con->prepare($query_update);
                $stmt_update->bind_param('iissssdi', $categoria_id, $marca_id, $producto_nombre, $producto_descripcion, $target_file, $target_file2, $producto_precio, $producto_id);

                if ($stmt_update->execute()) {
                    $success = 'Producto actualizado exitosamente.';
                } else {
                    $error = 'Error al actualizar el producto.';
                }
            }
        }
    }
}

// Obtener categorías y marcas
$query_categorias = "SELECT catId, catNombre FROM categoria";
$result_categorias = mysqli_query($con, $query_categorias);

$query_marcas = "SELECT marId, marNombre FROM marca";
$result_marcas = mysqli_query($con, $query_marcas);
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Editar Producto</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="gestionar-producto.php">Productos</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#productoDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-box"></i> Editar Producto
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="productoDetails" role="tabpanel">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible fade show"><?php echo $error; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_id" class="form-label">Categoría</label>
                                                    <select class="form-control" id="categoria_id" name="catId" required>
                                                        <?php while ($row_categoria = mysqli_fetch_assoc($result_categorias)): ?>
                                                            <option value="<?php echo $row_categoria['catId']; ?>" <?php echo ($row_categoria['catId'] == $producto['catId']) ? 'selected' : ''; ?>>
                                                                <?php echo $row_categoria['catNombre']; ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="marca_id" class="form-label">Marca</label>
                                                    <select class="form-control" id="marca_id" name="marId" required>
                                                        <?php while ($row_marca = mysqli_fetch_assoc($result_marcas)): ?>
                                                            <option value="<?php echo $row_marca['marId']; ?>" <?php echo ($row_marca['marId'] == $producto['marId']) ? 'selected' : ''; ?>>
                                                                <?php echo $row_marca['marNombre']; ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_nombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="producto_nombre" name="proNombre" value="<?php echo htmlspecialchars($producto['proNombre']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_descripcion" class="form-label">Descripción</label>
                                                    <textarea class="form-control" id="producto_descripcion" name="proDescripcion" required><?php echo htmlspecialchars($producto['proDescripcion']); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_img" class="form-label">Imagen Principal</label>
                                                    <input type="file" class="form-control" id="producto_img" name="proImg">
                                                    <img src="<?php echo htmlspecialchars($producto['proImg']); ?>" alt="<?php echo htmlspecialchars($producto['proNombre']); ?>" style="width: 100px; height: 100px; margin-top: 10px;">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_img2" class="form-label">Imagen Secundaria</label>
                                                    <input type="file" class="form-control" id="producto_img2" name="proImg2">
                                                    <img src="<?php echo htmlspecialchars($producto['proImg2']); ?>" alt="<?php echo htmlspecialchars($producto['proNombre']); ?>" style="width: 100px; height: 100px; margin-top: 10px;">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_precio" class="form-label">Precio</label>
                                                    <input type="number" step="0.01" class="form-control" id="producto_precio" name="proPrecio" required min="0" value="<?php echo htmlspecialchars($producto['proPrecio']); ?>">
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

    <?php include "../../footer.php"; ?>
</div>