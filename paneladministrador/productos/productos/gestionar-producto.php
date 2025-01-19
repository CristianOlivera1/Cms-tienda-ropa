<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// Variables para los campos del formulario
$producto_nombre = '';
$producto_descripcion = '';
$producto_precio = 0.0;
$producto_img = '';
$producto_img2 = '';
$categoria_id = '';
$marca_id = '';

// Variables de búsqueda y paginación
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = 10;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_nombre = trim($_POST['proNombre'] ?? '');
    $producto_descripcion = trim($_POST['proDescripcion'] ?? '');
    $producto_precio = isset($_POST['proPrecio']) ? (float)$_POST['proPrecio'] : 0.0;
    $producto_img = $_FILES['proImg'];
    $producto_img2 = $_FILES['proImg2'];
    $categoria_id = trim($_POST['catId']);
    $marca_id = trim($_POST['marId']);

    // Validación de campos
    if (empty($producto_nombre) || empty($producto_descripcion) || empty($producto_img['name']) || empty($producto_img2['name']) || $producto_precio <= 0 || empty($categoria_id) || empty($marca_id)) {
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
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true); // Crear el directorio si no existe
            }
            $unique_name1 = uniqid() . '-' . basename($producto_img["name"]);
            $target_file1 = $target_dir . $unique_name1; // Renombrar el archivo para evitar conflictos
            $imageFileType1 = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));

            $unique_name2 = uniqid() . '-' . basename($producto_img2["name"]);
            $target_file2 = $target_dir . $unique_name2; // Renombrar el archivo para evitar conflictos
            $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));

            // Validaciones de imagen
            if (getimagesize($producto_img["tmp_name"]) === false || getimagesize($producto_img2["tmp_name"]) === false) {
                $error = 'Uno de los archivos no es una imagen.';
            } elseif ($producto_img["size"] > 3000000 || $producto_img2["size"] > 3000000) {
                $error = 'Uno de los archivos es demasiado grande.';
            } elseif (!in_array($imageFileType1, ['jpg', 'jpeg', 'png', 'webp']) || !in_array($imageFileType2, ['jpg', 'jpeg', 'png', 'webp'])) {
                $error = 'Solo se permiten archivos JPG, JPEG, PNG y WEBP.';
            } else {
                // Manejar la carga de imágenes
                if (move_uploaded_file($producto_img["tmp_name"], $target_file1) && move_uploaded_file($producto_img2["tmp_name"], $target_file2)) {
                    // Inserción en la base de datos
                    $query_insert = "INSERT INTO producto (catId, marId, proNombre, proDescripcion, proImg, proImg2, proPrecio, proFechaRegistro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt_insert = $con->prepare($query_insert);
                    $stmt_insert->bind_param('iissssd', $categoria_id, $marca_id, $producto_nombre, $producto_descripcion, $unique_name1, $unique_name2, $producto_precio);
                    if ($stmt_insert->execute()) {
                        $success = 'Producto registrado exitosamente.';
                        // Limpiar los campos del formulario
                        $producto_nombre = '';
                        $producto_descripcion = '';
                        $producto_precio = 0.0;
                        $categoria_id = '';
                        $marca_id = '';
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

// Verificar el mensaje de éxito
if (isset($_GET['success'])) {
    $success = 'Producto registrado exitosamente.';
}

// Obtener el total de registros con filtros
$query_total = "SELECT COUNT(*) as total FROM producto WHERE proNombre LIKE ?";
$stmt_total = $con->prepare($query_total);
$search_param = "%$search%";
$stmt_total->bind_param('s', $search_param);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener productos con filtros y paginación
$query = "SELECT p.*, c.catNombre, m.marNombre FROM producto p
          JOIN categoria c ON p.catId = c.catId
          JOIN marca m ON p.marId = m.marId
          WHERE p.proNombre LIKE ?
          ORDER BY p.proId DESC
          LIMIT ? OFFSET ?";
$stmt = $con->prepare($query);
$stmt->bind_param('sii', $search_param, $registros_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();
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
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i class="fas fa-box"></i> Agregar Producto
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
                                                        <option value="">Seleccione una categoría</option>
                                                        <?php
                                                        $query_categorias = "SELECT * FROM categoria";
                                                        $result_categorias = mysqli_query($con, $query_categorias);
                                                        while ($row_categoria = mysqli_fetch_assoc($result_categorias)) {
                                                            $selected = ($categoria_id == $row_categoria['catId']) ? 'selected' : '';
                                                            echo "<option value='{$row_categoria['catId']}' $selected>{$row_categoria['catNombre']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="marca_id" class="form-label">Marca</label>
                                                    <select class="form-control" id="marca_id" name="marId" required>
                                                        <option value="">Seleccione una marca</option>
                                                        <?php
                                                        $query_marcas = "SELECT * FROM marca";
                                                        $result_marcas = mysqli_query($con, $query_marcas);
                                                        while ($row_marca = mysqli_fetch_assoc($result_marcas)) {
                                                            $selected = ($marca_id == $row_marca['marId']) ? 'selected' : '';
                                                            echo "<option value='{$row_marca['marId']}' $selected>{$row_marca['marNombre']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_nombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="producto_nombre" name="proNombre" required placeholder="Ingrese el nombre" value="<?php echo htmlspecialchars($producto_nombre); ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_descripcion" class="form-label">Descripción</label>
                                                    <textarea class="form-control" id="producto_descripcion" name="proDescripcion" required><?php echo htmlspecialchars($producto_descripcion); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_img" class="form-label">Imagen Principal</label>
                                                    <input type="file" class="form-control" id="producto_img" name="proImg" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_img2" class="form-label">Imagen Secundaria</label>
                                                    <input type="file" class="form-control" id="producto_img2" name="proImg2" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="producto_precio" class="form-label">Precio</label>
                                                    <input type="number" step="0.01" class="form-control" id="producto_precio" name="proPrecio" required min="0" value="<?php echo htmlspecialchars($producto_precio); ?>">
                                                </div>
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
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Productos</h5>
                        </div>
                        <div class="card-body">
                        <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="search" class="form-control" placeholder="Buscar marca" value="<?php echo htmlspecialchars($search); ?>" onkeyup="if(event.keyCode == 13) window.location.href='?search='+this.value">
                                        <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-end">
                                <!-- Paginación -->
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>#example"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                                </div>
                            </div>
                            <!-- Tabla de productos -->
                            <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Nombre del Producto</th>
                                        <th>Imagen Principal</th>
                                        <th>Precio</th>
                                        <th class="accion-col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $numero = $offset + 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $image_path1 = "../../recursos/uploads/producto/" . htmlspecialchars($row['proImg']);
                                        echo "<tr id='producto-{$row['proId']}' ondblclick='openProductModal({$row['proId']}, \"{$row['proNombre']}\", \"{$row['proDescripcion']}\", \"{$row['proImg']}\", \"{$row['proImg2']}\", {$row['proPrecio']}, \"{$row['catNombre']}\", \"{$row['marNombre']}\")'>
                                                <td>{$numero}</td>
                                                <td>" . htmlspecialchars($row['proNombre']) . "</td>
                                                <td><img src='" . $image_path1 . "' alt='" . htmlspecialchars($row['proNombre']) . "' style='width: 50px; height: 50px;'></td>
                                                <td>S/. " . htmlspecialchars($row['proPrecio']) . "</td>
                                                <td>
                                                    <a href='editarproducto.php?id={$row['proId']}' class='btn btn-soft-secondary btn-sm' aria-label='Editar' title='Editar'><i class='ri-pencil-fill'></i></a>
                                                    <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteProducto({$row['proId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill'></i></a>
                                                </td>
                                            </tr>";
                                        $numero++;
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="productModalLabel">Detalles del Producto</h4>
                    <div class="circle">
                    <button type="button" class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body d-flex">
                    <div class="me-4" style="flex: 1;">
                        <img id="modalProductImage1" src="" alt="Miniatura 1" class="img-fluid rounded thumbnail" onclick="swapImages()">
                    </div> 
                    <img id="modalProductImage2" src="" alt="Miniatura 2" class="img-fluid rounded main-image" style="max-width: 50%; height: auto;" >

                    <div style="flex: 1; padding-left: 20px;">
                        <h4 id="modalProductName" class="product-name">Polera Pell</h4>
                        <p id="modalProductPrice" class="text-precio">S/. 60.00</p>
                        <p><strong>Descripción:</strong></p>
                        <p id="modalProductDescription" class="product-description">Polera Pell casual y urbano</p>
                        <p><strong>Categoría:</strong> <span id="modalProductCategory" class="badge-total">Polera</span></p>
                        <p><strong>Marca:</strong> <span id="modalProductBrand" class="badge-total">Levis</span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../../recursos/js/script.js"></script>

</div>
<?php include "../../footer.php"; ?>