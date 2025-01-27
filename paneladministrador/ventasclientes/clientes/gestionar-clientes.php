<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// BUSCAR Clientes
$search = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($search) {
    $query = "SELECT id, cliNombre AS nombre, cliApellidoPaterno AS apellidoPaterno, cliApellidoMaterno AS apellidoMaterno 
              FROM cliente 
              WHERE cliApellidoPaterno LIKE ? OR cliApellidoMaterno LIKE ? OR cliNombre LIKE ?";
    
    $stmt = $con->prepare($query);
    $search_param = "%$search%";
    $stmt->bind_param('sss', $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    $clients = [];
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }

    echo json_encode($clients);
}
// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda y filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM cliente WHERE cliDni LIKE ? OR cliNombre LIKE ? OR cliApellidoPaterno LIKE ? OR cliApellidoMaterno LIKE ? OR cliCorreo LIKE ? OR cliFechaNacimiento LIKE ?";
$stmt_total = $con->prepare($query_total);
$search_param = "%$search%";
$stmt_total->bind_param('ssssss', $search_param, $search_param, $search_param, $search_param, $search_param,$cliFechaNacimiento);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliDni = trim($_POST['cliDni']);
    $cliNombre = trim($_POST['cliNombre']);
    $cliAp = trim($_POST['cliApellidoPaterno']);
    $cliAm = trim($_POST['cliApellidoMaterno']);
    $correo = trim($_POST['cliCorreo']);
    $cliNacimiento = trim($_POST['cliFechaNacimiento']);

    // Check for empty fields
    if (empty($cliNombre) || empty($cliAp) || empty($cliAm) || empty($correo) || empty($cliNacimiento)) {
        $error = 'Todos los campos son obligatorios excepto el DNI.';
    } else {
        // Check if the client is older than 18
        $birthDate = new DateTime($cliNacimiento);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;

        if ($age < 18) {
            $error = 'El cliente debe ser mayor de 18 años.';
        } else {
            // Prepare query to check for existing email
            $checkEmailQuery = "SELECT * FROM cliente WHERE cliCorreo = ?";
            $checkEmailStmt = $con->prepare($checkEmailQuery);
            $checkEmailStmt->bind_param('s', $correo);
            $checkEmailStmt->execute();
            $checkEmailResult = $checkEmailStmt->get_result();

            // Check if email already exists
            if ($checkEmailResult->num_rows > 0) {
                $error = 'El correo ya está registrado.';
            } else {
                // If DNI is provided, check if it's unique
                if (!empty($cliDni)) {
                    $checkDniQuery = "SELECT * FROM cliente WHERE cliDni = ?";
                    $checkDniStmt = $con->prepare($checkDniQuery);
                    $checkDniStmt->bind_param('s', $cliDni);
                    $checkDniStmt->execute();
                    $checkDniResult = $checkDniStmt->get_result();

                    // Check if DNI already exists
                    if ($checkDniResult->num_rows > 0) {
                        $error = 'El DNI ya está registrado.';
                    }
                }

                // If no errors, proceed to insert the new client
                if (empty($error)) {
                    $query = "INSERT INTO cliente (cliDni, cliNombre, cliApellidoPaterno, cliApellidoMaterno, cliCorreo, cliFechaNacimiento, cliFechaRegis) VALUES (?, ?, ?, ?, ?, ?, NOW())";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('ssssss', $cliDni, $cliNombre, $cliAp, $cliAm, $correo, $cliNacimiento);
                    if ($stmt->execute()) {
                        $success = 'Cliente registrado exitosamente.';
                    } else {
                        $error = 'Error al registrar el cliente.';
                    }
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
                                                    <input type="text" class="form-control" id="cliNombre" name="cliNombre" 
                                                    required oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliApellidoPaterno" class="form-label">Apellido Paterno</label>
                                                    <input type="text" class="form-control" id="cliApellidoPaterno" name="cliApellidoPaterno" 
                                                    required oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cliApellidoMaterno" class="form-label">Apellido Materno</label>
                                                    <input type="text" class="form-control" id="cliApellidoMaterno" name="cliApellidoMaterno" 
                                                    required oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">
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
                            <h5 class="card-title mb-0">Lista de Clientes <div class="badge-total">Total: <?php echo $total_registros ?> </div></h5>
                        </div>
                        <div class="alert-fk px-3 pt-3">
                        </div>
                        <div class="card-body">
                        <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" id="search" class="form-control" placeholder="Buscar cliente" value="<?php echo htmlspecialchars($search); ?>">
                                        <span class="input-group-text"><i class="ri-search-2-line"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select id="order_dir" class="form-select">
                                        <option value="DESC" <?php echo ($order_dir == 'DESC') ? 'selected' : ''; ?>>Descendente</option>
                                        <option value="ASC" <?php echo ($order_dir == 'ASC') ? 'selected' : ''; ?>>Ascendente</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex justify-content-end">
                                <!-- Paginación -->
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&order_dir=<?php echo htmlspecialchars($order_dir); ?>#example"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                                </div>
                            </div>
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Dni</th>
                                        <th>Nombre</th>
                                        <th>Apellido Paterno</th>
                                        <th>Apellido Materno</th>
                                        <th>Fecha de Nacimiento</th>
                                        <th>Correo</th>
                                        <th class="accion-col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                   $query = "SELECT * FROM cliente WHERE cliDni LIKE ? OR cliNombre LIKE ? OR cliApellidoPaterno LIKE ? OR cliApellidoMaterno LIKE ? OR cliFechaNacimiento LIKE ? OR cliCorreo LIKE ? ORDER BY cliId $order_dir LIMIT $registros_por_pagina OFFSET $offset";
                                   $stmt = $con->prepare($query);
                                   $stmt->bind_param('ssssss', $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);
                                   $stmt->execute();
                                   $result = $stmt->get_result();
                                   $numero_registro = $offset + 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr id='cliente-{$row['cliId']}'>
                                                <td>{$numero_registro}</td>
                                                <td>{$row['cliDni']}</td>
                                                <td>{$row['cliNombre']}</td>
                                                <td>{$row['cliApellidoPaterno']}</td>
                                                <td>{$row['cliApellidoMaterno']}</td>
                                                <td>{$row['cliFechaNacimiento']}</td>
                                                <td>{$row['cliCorreo']}</td>
                                                <td>
                                                <a href='editarcliente.php?id={$row['cliId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1' aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteCliente({$row['cliId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                </td>
                                            </tr>";
                                            $numero_registro++;
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