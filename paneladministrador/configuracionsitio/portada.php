<?php
include "../header.php";
include "../sidebar.php";

$error = '';
$success = '';
$usuarioId = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);

    if (empty($titulo) || empty($descripcion)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $query = "UPDATE portada SET porTitulo = ?, porDescripcion = ? WHERE porId = 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ss', $titulo, $descripcion);
        if ($stmt->execute()) {
            $success = 'Información de la portada actualizada exitosamente.';
            registrarActividad($con, $usuarioId,"Portada","Update", "Actualizó los datos de la portada. Título: " . htmlspecialchars($titulo) . ", Descripción: " . htmlspecialchars($descripcion));
        } else {
            $error = 'Error al actualizar la información de la portada.';
        }
    }
} else {
    $query = "SELECT * FROM portada WHERE porId = 1";
    $result = mysqli_query($con, $query);
    $portada = mysqli_fetch_assoc($result);
    $titulo = $portada['porTitulo'];
    $descripcion = $portada['porDescripcion'];
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Editar Portada</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Portada</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        </i> Editar Portada
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="portadaDetails" role="tabpanel">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible alert-outline fade show"><?php echo $error; ?> <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible alert-outline fade show"><?php echo $success; ?> <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <form method="POST" action="">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="titulo" class="form-label">Título</label>
                                                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="descripcion" class="form-label">Descripción</label>
                                                    <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo htmlspecialchars($descripcion); ?></textarea>
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
    <script src="../recursos/js/script.js"></script>
</div>

<?php include "../footer.php"; ?>