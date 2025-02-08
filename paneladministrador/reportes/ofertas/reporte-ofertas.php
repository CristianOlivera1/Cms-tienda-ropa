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
                        <h4 class="mb-sm-0">Reporte Ofertas</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Ofertas</a></li>
                                <li class="breadcrumb-item active">Reporte</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-8">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Grafico Reporte de Ofertas</h5>
                        </div>
                      <!-- Grafico -->
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
                                        <th>Precio normal</th>
                                        <th>Precio oferta</th>
                                        <th>Fecha de registro</th>
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
                                                <td>". number_format($row['precioNormal'], 2) . " Soles <br></td>
                                                <td>" . number_format($row['precioDescontado'], 2) . " Soles</td>
                                                <td>{$row['ofeFechaRegis']}</td>
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
    <script src="../../recursos/js/script.js"></script>
</div>

<?php include "../../footer.php"; ?>