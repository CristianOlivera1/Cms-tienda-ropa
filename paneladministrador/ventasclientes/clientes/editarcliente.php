<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];

    // Obtener los datos del cliente
    $query = "SELECT * FROM cliente WHERE cliId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if (!$cliente) {
        $error = 'Cliente no encontrado.';
    }
} else {
    $error = 'ID de cliente no proporcionado.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliNombre = trim($_POST['cliNombre']);
    $cliAp = trim($_POST['cliApellidoPaterno']);
    $cliAm = trim($_POST['cliApellidoMaterno']);
    $correo = trim($_POST['cliCorreo']);

    if (empty($cliNombre) || empty($cliAp) || empty($cliAm) || empty($correo)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $query = "UPDATE cliente SET cliNombre = ?, cliApellidoPaterno = ?, cliApellidoMaterno = ?, cliCorreo = ? WHERE cliId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ssssi', $cliNombre, $cliAp, $cliAm, $correo, $cliente_id);
        if ($stmt->execute()) {
            $success = 'Cliente actualizado exitosamente.';
            // Actualizar los datos del cliente
            $cliente['cliNombre'] = $cliNombre;
            $cliente['cliApellidoPaterno'] = $cliAp;
            $cliente['cliApellidoMaterno'] = $cliAm;
            $cliente['cliCorreo'] = $correo;
        } else {
            $error = 'Error al actualizar el cliente.';
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
                        <h4 class="mb-sm-0">Editar Cliente</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Clientes</a></li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#clienteDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-user"></i> Editar Cliente
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="clienteDetails" role="tabpanel">
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
                                                    <label for="cliNombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="cliNombre" name="cliNombre" value="<?php echo htmlspecialchars($cliente['cliNombre']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliApellidoPaterno" class="form-label">Apellido Paterno</label>
                                                    <input type="text" class="form-control" id="cliApellidoPaterno" name="cliApellidoPaterno" value="<?php echo htmlspecialchars($cliente['cliApellidoPaterno']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliApellidoMaterno" class="form-label">Apellido Materno</label>
                                                    <input type="text" class="form-control" id="cliApellidoMaterno" name="cliApellidoMaterno" value="<?php echo htmlspecialchars($cliente['cliApellidoMaterno']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliCorreo" class="form-label">Correo</label>
                                                    <input type="email" class="form-control" id="cliCorreo" name="cliCorreo" value="<?php echo htmlspecialchars($cliente['cliCorreo']); ?>" required>
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