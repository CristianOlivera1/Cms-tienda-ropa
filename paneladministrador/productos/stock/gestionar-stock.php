<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// Variables para los campos del formulario
$producto_nombre = '';
$producto_descripcion = '';
$producto_precio = 0;
$filter_nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$filter_categoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : '';
$filter_marca = isset($_GET['marca']) ? (int)$_GET['marca'] : '';

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros con filtros
$query_total = "SELECT COUNT(*) as total FROM producto p WHERE (p.proNombre LIKE ? AND (p.catId = ? OR ? = '') AND (p.marId = ? OR ? = '') )";
$stmt_total = $con->prepare($query_total);
$filter_nombre_param = "%$filter_nombre%";
$stmt_total->bind_param('siiii', $filter_nombre_param, $filter_categoria, $filter_categoria, $filter_marca, $filter_marca);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener productos con filtros
$query = "SELECT p.*, c.catNombre, m.marNombre FROM producto p
          JOIN categoria c ON p.catId = c.catId
          JOIN marca m ON p.marId = m.marId
          WHERE (p.proNombre LIKE ? AND (p.catId = ? OR ? = '') AND (p.marId = ? OR ? = '') )
          ORDER BY p.proId DESC LIMIT ? OFFSET ?";
$stmt = $con->prepare($query);
$stmt->bind_param('siiiiii', $filter_nombre_param, $filter_categoria, $filter_categoria, $filter_marca, $filter_marca, $registros_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_nombre = trim($_POST['proNombre'] ?? '');
    $producto_descripcion = trim($_POST['proDescripcion'] ?? '');
    $producto_precio = isset($_POST['proPrecio']) ? (float)$_POST['proPrecio'] : 0;
    $producto_img = $_FILES['proImg'];
    $producto_img2 = $_FILES['proImg2'];

    // Validación de campos
    if (empty($producto_nombre) || empty($producto_descripcion) || empty($producto_img['name']) || empty($producto_img2['name']) || $producto_precio < 0) {
        $error = 'Todos los campos son obligatorios y deben ser válidos.';
    } else {
        // Verificar si ya existe un producto con el mismo nombre
        $query_check = "SELECT COUNT(*) as count FROM producto WHERE proNombre = ?";
        $stmt_check = $con->prepare($query_check);
        $stmt_check->bind_param('s', $producto_nombre);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $count = $result_check->fetch_assoc()['count'];

        if ($count > 0) {
            $error = 'Ya existe un producto con este nombre.';
        } else {
            // Manejar la carga de imágenes
            $target_dir = "../../recursos/uploads/producto/";
            $target_file = $target_dir . basename($producto_img["name"]);
            $target_file2 = $target_dir . basename($producto_img2["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

            // Validaciones de imagen
            if (getimagesize($producto_img["tmp_name"]) === false || getimagesize($producto_img2["tmp_name"]) === false) {
                $error = 'Uno de los archivos no es una imagen.';
            } elseif ($producto_img["size"] > 30000000 || $producto_img2["size"] > 30000000) {
                $error = 'Uno de los archivos es demasiado grande.';
            } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg']) || !in_array($imageFileType2, ['jpg', 'png', 'jpeg'])) {
                $error = 'Solo se permiten archivos JPG, JPEG, PNG.';
            } else {
                if (move_uploaded_file($producto_img["tmp_name"], $target_file) && move_uploaded_file($producto_img2["tmp_name"], $target_file2)) {
                    // Inserción en la base de datos
                    $query_insert = "INSERT INTO producto (catId, marId, proNombre, proDescripcion, proImg, proImg2, proPrecio, proFechaRegistro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt_insert = $con->prepare($query_insert);
                    $stmt_insert->bind_param('iissssi', $_POST['catId'], $_POST['marId'], $producto_nombre, $producto_descripcion, $target_file, $target_file2, $producto_precio);
                    if ($stmt_insert->execute()) {
                        $success = 'Producto registrado exitosamente.';
                        // Limpiar los campos del formulario
                        $producto_nombre = '';
                        $producto_descripcion = '';
                        $producto_precio = 0;
                        $_POST['catId'] = '';
                        $_POST['marId'] = '';
                    } else {
                        $error = 'Error al registrar el producto.';
                    }
                } else {
                    $error = 'Error al cargar las imágenes.';
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
                        <h4 class="mb-sm-0">Gestionar Productos</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Productos</a></li>
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
                            <h5 class="card-title mb-0">Agregar Producto</h5>
                        </div>

                        <div class="card-body p-4">
                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show"><?php echo $error; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                            <?php endif; ?>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label for="categoria_id" class="form-label">Categoría</label>
                                        <select class="form-control" id="categoria_id" name="catId" required>
                                            <option value="">Seleccione una categoría</option>
                                            <?php
                                            $query_categorias = "SELECT * FROM categoria";
                                            $result_categorias = mysqli_query($con, $query_categorias);
                                            while ($row_categoria = mysqli_fetch_assoc($result_categorias)) {
                                                $selected = ($_POST['catId'] == $row_categoria['catId']) ? 'selected' : '';
                                                echo "<option value='{$row_categoria['catId']}' $selected>{$row_categoria['catNombre']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="marca_id" class="form-label">Marca</label>
                                        <select class="form-control" id="marca_id" name="marId" required>
                                            <option value="">Seleccione una marca</option>
                                            <?php
                                            $query_marcas = "SELECT * FROM marca";
                                            $result_marcas = mysqli_query($con, $query_marcas);
                                            while ($row_marca = mysqli_fetch_assoc($result_marcas)) {
                                                $selected = ($_POST['marId'] == $row_marca['marId']) ? 'selected' : '';
                                                echo "<option value='{$row_marca['marId']}' $selected>{$row_marca['marNombre']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="producto_nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="producto_nombre" name="proNombre" required placeholder="Ingrese el nombre" value="<?php echo htmlspecialchars($producto_nombre); ?>">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="producto_descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="producto_descripcion" name="proDescripcion" required><?php echo htmlspecialchars($producto_descripcion); ?></textarea>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="producto_img" class="form-label">Imagen Principal</label>
                                        <input type="file" class="form-control" id="producto_img" name="proImg" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="producto_img2" class="form-label">Imagen Secundaria</label>
                                        <input type="file" class="form-control" id="producto_img2" name="proImg2" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="producto_precio" class="form-label">Precio</label>
                                        <input type="number" step="0.01" class="form-control" id="producto_precio" name="proPrecio" required min="0" value="<?php echo htmlspecialchars($producto_precio); ?>">
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
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Productos <span class="badge bg-secondary">Total: <?php echo $total_registros; ?></span></h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="" class="d-flex mb-3" id="filterForm">
                                <input type="text" name="nombre" class="form-control me-2" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars($filter_nombre); ?>" onchange="this.form.submit()">
                                <select name="categoria" class="form-control me-2" onchange="this.form.submit()">
                                    <option value="">Todas las categorías</option>
                                    <?php
                                    $query_categorias = "SELECT * FROM categoria";
                                    $result_categorias = mysqli_query($con, $query_categorias);
                                    while ($row_categoria = mysqli_fetch_assoc($result_categorias)) {
                                        $selected = ($filter_categoria == $row_categoria['catId']) ? 'selected' : '';
                                        echo "<option value='{$row_categoria['catId']}' $selected>{$row_categoria['catNombre']}</option>";
                                    }
                                    ?>
                                </select>
                                <select name="marca" class="form-control me-2" onchange="this.form.submit()">
                                    <option value="">Todas las marcas</option>
                                    <?php
                                    $query_marcas = "SELECT * FROM marca";
                                    $result_marcas = mysqli_query($con, $query_marcas);
                                    while ($row_marca = mysqli_fetch_assoc($result_marcas)) {
                                        $selected = ($filter_marca == $row_marca['marId']) ? 'selected' : '';
                                        echo "<option value='{$row_marca['marId']}' $selected>{$row_marca['marNombre']}</option>";
                                    }
                                    ?>
                                </select>
                                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar</button>
                            </form>
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre del Producto</th>
                                        <th>Imagen Principal</th>
                                        <th>Precio</th>
                                        <th class="accion-col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr id='producto-{$row['proId']}' ondblclick='openProductModal({$row['proId']}, \"{$row['proNombre']}\", \"{$row['proDescripcion']}\", \"{$row['proImg']}\", {$row['proPrecio']}, \"{$row['catNombre']}\", \"{$row['marNombre']}\")'>
                                                <td>{$row['proNombre']}</td>
                                                <td><img src='{$row['proImg']}' alt='{$row['proNombre']}' style='width: 50px; height: 50px;'></td>
                                                <td>{$row['proPrecio']}</td>
                                                <td>
                                                    <a href='editarproducto.php?id={$row['proId']}' class='btn btn-soft-secondary btn-sm' aria-label='Editar' title='Editar'><i class='ri-pencil-fill'></i></a>
                                                    <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteProducto({$row['proId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill'></i></a>
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

    <!-- Modal para confirmación de eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este producto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmDeleteBtnProducto" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar detalles del producto -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalProductImage" src="" alt="" style="width: 100%; height: auto;">
                    <p><strong>Nombre: </strong><span id="modalProductName"></span></p>
                    <p><strong>Descripción: </strong><span id="modalProductDescription"></span></p>
                    <p><strong>Categoria: </strong><span id="modalProductCategory"></span></p>
                    <p><strong>Marca: </strong><span id="modalProductBrand"></span></p>
                    <p><strong>Precio: </strong><span id="modalProductPrice"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php"; ?>