<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM categoria";
$result_total = mysqli_query($con, $query_total);
$total_registros = mysqli_fetch_assoc($result_total)['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria_nombre = trim($_POST['catNombre']);
    $categoria_descripcion = trim($_POST['catDescripcion']);
    $categoria_detalle = trim($_POST['catDetalle']);
    $categoria_img = $_FILES['catImg'];

    if (empty($categoria_nombre) || empty($categoria_descripcion) || empty($categoria_detalle) || empty($categoria_img['name'])) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Manejar la carga de la imagen
        $target_dir = "../../recursos/uploads/categoria/";
        $target_file = $target_dir . basename($categoria_img["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar si el archivo es una imagen real
        $check = getimagesize($categoria_img["tmp_name"]);
        if ($check === false) {
            $error = 'El archivo no es una imagen.';
        } elseif ($categoria_img["size"] > 3000000) {
            $error = 'El archivo es demasiado grande.';
        } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
            $error = 'Solo se permiten archivos JPG, JPEG, PNG.';
        } else {
            // Verificar si la categoría ya existe
            $query = "SELECT * FROM categoria WHERE catNombre = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $categoria_nombre);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $error = 'La categoría ya existe.';
            } else {
                if (move_uploaded_file($categoria_img["tmp_name"], $target_file)) {
                    $query = "INSERT INTO categoria (catNombre, catDescripcion, catDetalle, catImg, catFechaRegis) VALUES (?, ?, ?, ?, NOW())";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('ssss', $categoria_nombre, $categoria_descripcion, $categoria_detalle, $target_file);
                    if ($stmt->execute()) {
                        $success = 'Categoría registrada exitosamente.';
                    } else {
                        $error = 'Error al registrar la categoría.';
                    }
                } else {
                    $error = 'Error al cargar la imagen.';
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
                        <h4 class="mb-sm-0">Gestionar Categorías</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Categorías</a></li>
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
                                        <i class="fas fa-tags"></i> Agregar Categoría
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
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_nombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="categoria_nombre" name="catNombre" required placeholder="Ingrese el nombre">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_descripcion" class="form-label">Descripción</label>
                                                    <textarea class="form-control" id="categoria_descripcion" name="catDescripcion" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_detalle" class="form-label">Detalle</label>
                                                    <textarea class="form-control" id="categoria_detalle" name="catDetalle" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="categoria_img" class="form-label">Imagen</label>
                                                    <input type="file" class="form-control" id="categoria_img" name="catImg" required>
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
                            <h5 class="card-title mb-0">Lista de Categorías</h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre de la Categoría</th>
                                        <th>Descripción</th>
                                        <th>Imagen</th>
                                        <th class="accion-col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM categoria ORDER BY catId DESC LIMIT $registros_por_pagina OFFSET $offset";
                                    $result = mysqli_query($con, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr id='categoria-{$row['catId']}'>
                                                <td>{$row['catNombre']}</td>
                                                <td>{$row['catDescripcion']}</td>
                                                <td><img src='{$row['catImg']}' alt='{$row['catNombre']}' style='width: 50px; height: 50px;'></td>
                                                <td>
                                                  <a href='editarcategoria.php?id={$row['catId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1 aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                    <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteCategoria({$row['catId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                </td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <!-- Paginación -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <?php if ($pagina_actual > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>" tabindex="-1">Anterior</a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                        <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pagina_actual < $total_paginas): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>">Siguiente</a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#">Siguiente</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta categoría?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnCategoria">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php";?>