<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// Eliminar ofertas expiradas
$query_delete_expired = "DELETE FROM oferta WHERE ofeTiempo < NOW()";
$con->query($query_delete_expired); // Ejecuta la consulta

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_param = "%$search%";

// Inicializar la variable
$total_registros = 0;

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM oferta WHERE ofePorcentaje LIKE ?";
$params = [$search_param];

$stmt_total = $con->prepare($query_total);
$stmt_total->bind_param('s', $params[0]);
$stmt_total->execute();
$result_total = $stmt_total->get_result();

if ($result_total) {
    $total_registros = $result_total->fetch_assoc()['total'];
}
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stoId = trim($_POST['stoId']);
    $ofePorcentaje = trim($_POST['ofePorcentaje']);
    $ofeTiempo = trim($_POST['ofeTiempo']);

    if (empty($stoId) || empty($ofePorcentaje) || empty($ofeTiempo)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Verificar si ya existe una oferta para este stock
        $query_check = "SELECT COUNT(*) as count FROM oferta WHERE stoId = ?";
        $stmt_check = $con->prepare($query_check);
        $stmt_check->bind_param('i', $stoId);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $count = $result_check->fetch_assoc()['count'];

        if ($count > 0) {
            $error = 'Este stock ya tiene una oferta registrada.';
        } else {
            // Insertar nueva oferta
            $query = "INSERT INTO oferta (stoId, ofePorcentaje, ofeTiempo, ofeFechaRegis) VALUES (?, ?, ?, NOW())";
            $stmt = $con->prepare($query);
            $stmt->bind_param('ids', $stoId, $ofePorcentaje, $ofeTiempo);
            if ($stmt->execute()) {
                $success = 'Oferta registrada exitosamente.';
            } else {
                $error = 'Error al registrar la oferta.';
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
                        <h4 class="mb-sm-0">Gestionar Ofertas</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-8">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Agregar Oferta</h5>
                        </div>

                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo $error; ?>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Éxito!</strong> <?php echo $success; ?>
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>
                            <?php endif; ?>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="stoId" class="form-label">Stock</label>
                                    <select class="form-select" id="stoId" name="stoId" required>
                                        <?php
                                        $query = "SELECT stock.stoId,producto.proNombre FROM stock
                                                inner join producto on producto.proId=stock.proId"; // Asegúrate de que esta tabla tenga datos
                                        $result = mysqli_query($con, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['stoId']}'>{$row['proNombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="ofePorcentaje" class="form-label">Porcentaje de Descuento</label>
                                    <input type="number" class="form-control" id="ofePorcentaje" name="ofePorcentaje" required min="0" max="100">
                                </div>
                                <div class="mb-3">
                                    <label for="ofeTiempo" class="form-label">Tiempo de Oferta (Fecha y Hora)</label>
                                    <input type="datetime-local" class="form-control" id="ofeTiempo" name="ofeTiempo" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Registrar Oferta</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Ofertas <div class="badge-total">Total: <?php echo $total_registros; ?></div></h5>
                        </div>
                        <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 mt-3">
                                <input type="text" id="search" class="form-control me-2" placeholder="Buscar por porcentaje" 
                                    value="<?php echo htmlspecialchars($search); ?>" 
                                    oninput="searchOffers(this.value)">
                            </div>
                            <div class="col-md-6 mt-3 d-flex justify-content-end ">
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

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th>N</th>
                                        <th>Producto</th>
                                        <th>Porcentaje</th>
                                        <th>Tiempo de Oferta</th>
                                        <th>Fecha de Registro</th>
                                        <th>Precio normal</th>
                                        <th>Precio oferta</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "
                                    SELECT p.proNombre,o.ofeId, s.stoId, o.ofePorcentaje, o.ofeTiempo, o.ofeFechaRegis, p.proPrecio AS precioNormal,
                                           (p.proPrecio * (1 - o.ofePorcentaje / 100)) AS precioDescontado
                                    FROM oferta o 
                                    INNER JOIN stock s ON o.stoId = s.stoId 
                                    INNER JOIN producto p ON s.proId = p.proId 
                                    WHERE (o.ofePorcentaje LIKE ?)
                                    ORDER BY o.ofeId DESC LIMIT $registros_por_pagina OFFSET $offset";

                                    $stmt = $con->prepare($query);
                                    $stmt->bind_param('s', $params[0]);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $numeracion=$offset+1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr id='oferta-{$row['ofeId']}'>
                                                       <td>{$numeracion}</td>
                                                <td>{$row['proNombre']}</td>
                                                <td>{$row['ofePorcentaje']}%</td>
                                                <td>{$row['ofeTiempo']}</td>
                                                <td>{$row['ofeFechaRegis']}</td>
                                                <td>" . number_format($row['precioNormal'], 2) . " Soles <br>
                                                    
                                                </td>
                                                <td>
                                                    " . number_format($row['precioDescontado'], 2) . " Soles
                                                </td>
                                                <td>
                                                    <a href='editaroferta.php?id={$row['ofeId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1' aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                    <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteOfertas({$row['ofeId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                </td>
                                            </tr>";
                                            $numeracion++;
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
                    ¿Estás seguro de que deseas eliminar esta oferta?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnOferta">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php"; ?>