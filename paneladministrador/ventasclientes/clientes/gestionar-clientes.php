<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliDni = trim($_POST['cliDni']);
    $cliNombre = trim($_POST['cliNombre']);
    $cliAp = trim($_POST['cliApellidoPaterno']);
    $cliAm = trim($_POST['cliApellidoMaterno']);
    $correo = trim($_POST['cliCorreo']);
    $fechaNacimiento = trim($_POST['cliFechaNacimiento']); // Se obtiene en formato YYYY-MM-DD

    // Check for empty fields
    if (empty($cliNombre) || empty($cliAp) || empty($cliAm) || empty($correo) || empty($fechaNacimiento)) {
        $error = 'Todos los campos son obligatorios excepto el DNI.';
    } else {
        // Validar si la fecha de nacimiento es válida y si el cliente es mayor de 18 años
        $fechaNacimientoDate = new DateTime($fechaNacimiento);
        $hoy = new DateTime();

        // Calcular la edad
        $edad = $hoy->diff($fechaNacimientoDate)->y;

        if ($edad < 18) {
            $error = 'El cliente debe ser mayor de 18 años.';
        }

        // Verificar si el DNI o el correo ya existen
        if (empty($error)) {
            $queryDni = "SELECT COUNT(*) FROM cliente WHERE cliDni = ?";
            $stmtDni = $con->prepare($queryDni);
            $stmtDni->bind_param('s', $cliDni);
            $stmtDni->execute();
            $stmtDni->bind_result($dniCount);
            $stmtDni->fetch();
            $stmtDni->close();

            if ($dniCount > 0) {
                $error = 'El DNI ya está registrado.';
            } else {
                $queryCorreo = "SELECT COUNT(*) FROM cliente WHERE cliCorreo = ?";
                $stmtCorreo = $con->prepare($queryCorreo);
                $stmtCorreo->bind_param('s', $correo);
                $stmtCorreo->execute();
                $stmtCorreo->bind_result($correoCount);
                $stmtCorreo->fetch();
                $stmtCorreo->close();

                if ($correoCount > 0) {
                    $error = 'El correo ya está registrado.';
                }
            }
        }

        // If no errors, proceed to insert the new client
        if (empty($error)) {
            $query = "INSERT INTO cliente (cliDni, cliNombre, cliApellidoPaterno, cliApellidoMaterno, cliCorreo, cliFechaNacimiento, cliFechaRegis) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $con->prepare($query);
            $stmt->bind_param('ssssss', $cliDni, $cliNombre, $cliAp, $cliAm, $correo, $fechaNacimiento);
            if ($stmt->execute()) {
                $success = 'Cliente registrado exitosamente.';
            } else {
                $error = 'Error al registrar el cliente.';
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
                        <h4 class="mb-sm-0">Gestionar Clientes</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Clientes</a></li>
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
                                        <i class="fas fa-user"></i> Agregar Cliente
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
                                                    <label for="cliDni" class="form-label">DNI</label>
                                                    <input type="number" class="form-control" id="cliDni" name="cliDni" min="0" max="99999999" oninput="this.value = this.value.slice(0, 8);" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliNombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="cliNombre" name="cliNombre" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliApellidoPaterno" class="form-label">Apellido Paterno</label>
                                                    <input type="text" class="form-control" id="cliApellidoPaterno" name="cliApellidoPaterno" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliApellidoMaterno" class="form-label">Apellido Materno</label>
                                                    <input type="text" class="form-control" id="cliApellidoMaterno" name="cliApellidoMaterno" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliCorreo" class="form-label">Correo</label>
                                                    <input type="email" class="form-control" id="cliCorreo" name="cliCorreo" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                                    <input type="date" class="form-control" id="cliFechaNacimiento" name="cliFechaNacimiento" required>
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
                            <h5 class="card-title mb-0">Lista de Clientes</h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Dni</th>
                                        <th>Nombre</th>
                                        <th>Apellido Paterno</th>
                                        <th>Apellido Materno</th>
                                        <th>Correo</th>
                                        <th>Fecha de Nacimiento</th>
                                        <th class="accion-col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM cliente ORDER BY cliId DESC";
                                    $result = mysqli_query($con, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr id='cliente-{$row['cliId']}'>
                                                <td>{$row['cliDni']}</td>
                                                <td>{$row['cliNombre']}</td>
                                                <td>{$row['cliApellidoPaterno']}</td>
                                                <td>{$row['cliApellidoMaterno']}</td>
                                                <td>{$row['cliCorreo']}</td>
                                                <td>{$row['cliFechaNacimiento']}</td>
                                                <td>
                                                <a href='editarcliente.php?id={$row['cliId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1' aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteCliente({$row['cliId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
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

    <!-- Modal de confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este cliente?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnCliente">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/script.js"></script>
</div>

<?php
include "../../footer.php";
?>