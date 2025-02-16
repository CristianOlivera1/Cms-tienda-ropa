<?php
include "../../header.php";
include "../../sidebar.php";

// Configuración de la paginación
$registros_por_pagina = 15;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Variables de búsqueda y paginación
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$filter_marca = isset($_GET['marca']) ? trim($_GET['marca']) : '';
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = 10;
$offset = ($pagina_actual - 1) * $registros_por_pagina;
// Obtener el término de búsqueda y filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

// Obtener el filtro de fecha
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';

// Obtener el total de registros
$query_total = "
    SELECT COUNT(*) as total 
    FROM ventas v 
    INNER JOIN cliente c ON v.cliId = c.cliId 
    INNER JOIN detalleventa dv ON v.venId = dv.venId 
    INNER JOIN stock s ON dv.stoId = s.stoId 
    INNER JOIN producto p ON s.proId = p.proId 
    WHERE c.cliNombre LIKE ?";

$stmt_total = $con->prepare($query_total);
$search_param = "%$search%";
$stmt_total->bind_param('s', $search_param);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Gestión de Ventas</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item active">Detalles de Ventas</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row ptb_25">
                <div class="col-12 mt-5">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-primary text-white rounded-top-4 py-3">
                            <h5 class="card-title mb-0 text-center text-white">Reportes de Ventas</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Filtros de Fecha -->
                            <div class="container mt-5">
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <label for="fecha_desde" class="form-label text-muted">Desde:</label>
            <div class="input-group">
                <input type="date" id="fecha_desde" class="form-control rounded-pill shadow-sm">
            </div>
        </div>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

        <div class="col-md-4">
            <label for="fecha_hasta" class="form-label text-muted">Hasta:</label>
            <div class="input-group">
                <input type="date" id="fecha_hasta" class="form-control rounded-pill shadow-sm">
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-secondary rounded-pill shadow-sm w-100" onclick="cargarReporte('rango')">Filtrar</button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="fechaErrorModal" tabindex="-1" aria-labelledby="fechaErrorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fechaErrorModalLabel">Error de Fechas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    La fecha 'Desde' no puede ser mayor que la fecha 'Hasta'. Por favor, selecciona un rango de fechas válido.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>


                            <!-- Botón para generar el PDF -->
                            <div class="row mb-4 g-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button class="btn btn-success rounded-pill shadow-sm" onclick="descargarPDF()">
                                        <i class="fa fa-file-pdf"></i> Generar PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function cargarReporte(periodo) {
        let url = 'obtener_reporte_rango.php';
        let params = {};

        if (periodo === 'rango') {
            params.filtro = 'rango';
            params.fecha_desde = document.getElementById('fecha_desde').value;
            params.fecha_hasta = document.getElementById('fecha_hasta').value;
        } else {
            params.filtro = periodo;
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(params)
            })
            .then(response => response.json())
            .then(data => {
                actualizarTabla(data.ventas);
            })
            .catch(error => console.error('Error:', error));
    }

    function descargarPDF() {
        let fechaDesde = document.getElementById('fecha_desde').value;
        let fechaHasta = document.getElementById('fecha_hasta').value;
        let search = new URLSearchParams(window.location.search).get('search') || '';

        let url = `generar_pdf_filtrado.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&search=${search}`;
        window.open(url, '_blank');
    }

    function actualizarTabla(ventas) {
        const tbody = document.querySelector('#detallesTable tbody');
        tbody.innerHTML = ''; // Limpiar la tabla

        ventas.forEach(venta => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${venta.venId}</td>
                <td>${venta.cliNombre}</td>
                <td>${venta.Apellidos}</td>
                <td>${venta.Estado}</td>
                <td>${venta.cliCorreo}</td>
                <td>${venta.cliDni}</td>
                <td>${venta.total}</td>
                <td>${venta.venFechaRegis}</td>
                <td>
                    <a href='ver-detalles-venta.php?venId=${venta.venId}' class='btn btn-info me-2'>
                        <i class='fa fa-eye'></i> 
                    </a>
                    <a href='reporte-detalles-ventas.php?venId=${venta.venId}' target='_blank' class='btn btn-info'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-file-earmark-pdf' viewBox='0 0 16 16'>
                            <path d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z'/>
                            <path d='M4.603 6.087a.81.81 0 0 1 .202.404c.507.2.813.633.813 1.109 0 .476-.306.908-.813 1.109a.81.81 0 0 1-.202.404.807.807 0 0 1-.202-.404C3.906 8.005 3.5 7.572 3.5 7c0-.572.406-1.005.901-1.309a.807.807 0 0 1 .202-.404zM7.5 9.5v-5h-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h.5zm1.5-3.5v-2h.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H9zm-2.5 0v-2h.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H7z'/>
                        </svg>
                    </a>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
</script>

<div class="d-flex justify-content-end">
    <a href="reporte-ventas.php" target="_blank" class="btn btn-info me-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 32 32">
            <path fill="#909090" d='m24.1 2.072l5.564 5.8v22.056H8.879V30h20.856V7.945z' />
            <path fill="#f4f4f4" d="M24.031 2H8.808v27.928h20.856V7.873z" />
            <path fill="#7a7b7c" d="M8.655 3.5h-6.39v6.827h20.1V3.5z" />
            <path fill="#dd2025" d="M22.472 10.211H2.395V3.379h20.077z" />
            <path fill="#464648" d="M9.052 4.534H7.745v4.8h1.028V7.715L9 7.728a2 2 0 0 0 .647-.117a1.4 1.4 0 0 0 .493-.291a1.2 1.2 0 0 0 .335-.454a2.1 2.1 0 0 0 .105-.908a2.2 2.2 0 0 0-.114-.644a1.17 1.17 0 0 0-.687-.65a2 2 0 0 0-.409-.104a2 2 0 0 0-.319-.026m-.189 2.294h-.089v-1.48h.193a.57.57 0 0 1 .459.181a.92.92 0 0 1 .183.558c0 .246 0 .469-.222.626a.94.94 0 0 1-.524.114m3.671-2.306c-.111 0-.219.008-.295.011L12 4.538h-.78v4.8h.918a2.7 2.7 0 0 0 1.028-.175a1.7 1.7 0 0 0 .68-.491a1.9 1.9 0 0 0 .373-.749a3.7 3.7 0 0 0 .114-.949a4.4 4.4 0 0 0-.087-1.127a1.8 1.8 0 0 0-.4-.733a1.6 1.6 0 0 0-.535-.4a2.4 2.4 0 0 0-.549-.178a1.3 1.3 0 0 0-.228-.017m-.182 3.937h-.1V5.392h.013a1.06 1.06 0 0 1 .6.107a1.2 1.2 0 0 1 .324.4a1.3 1.3 0 0 1 .142.526c.009.22 0 .4 0 .549a3 3 0 0 1-.033.513a1.8 1.8 0 0 1-.169.5a1.1 1.1 0 0 1-.363.36a.67.67 0 0 1-.416.106m5.08-3.915H15v4.8h1.028V7.434h1.3v-.892h-1.3V5.43h1.4v-.892" />
            <path fill="#dd2025" d="M21.781 20.255s3.188-.578 3.188.511s-1.975.646-3.188-.511m-2.357.083a7.5 7.5 0 0 0-1.473.489l.4-.9c.4-.9.815-2.127.815-2.127a14 14 0 0 0 1.658 2.252a13 13 0 0 0-1.4.288Zm-1.262-6.5c0-.949.307-1.208.546-1.208s.508.115.517.939a10.8 10.8 0 0 1-.517 2.434a4.4 4.4 0 0 1-.547-2.162Zm-4.649 10.516c-.978-.585 2.051-2.386 2.6-2.444c-.003.001-1.576 3.056-2.6 2.444M25.9 20.895c-.01-.1-.1-1.207-2.07-1.16a14 14 0 0 0-2.453.173a12.5 12.5 0 0 1-2.012-2.655a11.8 11.8 0 0 0 .623-3.1c-.029-1.2-.316-1.888-1.236-1.878s-1.054.815-.933 2.013a9.3 9.3 0 0 0 .665 2.338s-.425 1.323-.987 2.639s-.946 2.006-.946 2.006a9.6 9.6 0 0 0-2.725 1.4c-.824.767-1.159 1.356-.725 1.945c.374.508 1.683.623 2.853-.91a23 23 0 0 0 1.7-2.492s1.784-.489 2.339-.623s1.226-.24 1.226-.24s1.629 1.639 3.2 1.581s1.495-.939 1.485-1.035" />
        </svg>
        Imprimir ventas completadas
    </a>
    <div class="col-md-3" >
        <form method="GET" action="">
            <select name="marca" class="form-select" style="height: 48px;" onchange="this.form.submit()">
                <option value="">Todos los Estados</option> 
                <?php
                $query_marcas = "SELECT * FROM estadoventa ";
                $result_marcas = mysqli_query($con, $query_marcas);
                while ($row_marca = mysqli_fetch_assoc($result_marcas)) {
                    // Cambiar el valor por defecto a '1' para que muestre 'Pendiente'
                    $selected = ((isset($_GET['marca']) && $_GET['marca'] == $row_marca['estVenId']) ? 'selected' : 
                                (!isset($_GET['marca']) && $row_marca['estVenId'] == 1)) ? 'selected' : '';
                    echo "<option value='{$row_marca['estVenId']}' $selected>{$row_marca['estVenNombre']}</option>";
                }
                ?>
            </select>
        </form>
    </div>
    <div class="col-md-3">
          <!-- Paginación -->
          <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagina_actual > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>&search=<?php echo $search; ?>&marca=<?php echo $filter_marca; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                <a class="page-link" href="?pagina=<?php echo $i; ?>&search=<?php echo $search; ?>&marca=<?php echo $filter_marca; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($pagina_actual < $total_paginas): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>&search=<?php echo $search; ?>&marca=<?php echo $filter_marca; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
</div>

</div>
<div class="row" style="margin-left: 250px; margin-right: 10px;">
    <div class="col-12 mt-3">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 py-3">
                <h5 class="card-title mb-0 text-center text-white">Lista de Ventas</h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-hover table-bordered text-center align-middle" id="detallesTable" style="min-width: 1000px;">
                        <thead class="table-light">
                            <tr>
                                <th>Código de venta</th>
                                <th>Nombre del cliente</th>
                                <th>Apellidos</th>
                                <th>Estado</th>
                                <th>Correo</th>
                                <th>Dni</th>
                                <th>Precio Total</th>
                                <th>Fecha de Registro</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Obtener el estado seleccionado
                            $filter_marca = isset($_GET['marca']) ? $_GET['marca'] : '1';

                            // Consulta corregida con CONCAT para unir apellidos correctamente
                            $query = "SELECT 
                                v.venId, 
                                c.cliNombre, 
                                CONCAT(c.cliApellidoPaterno, ' ', c.cliApellidoMaterno) AS Apellidos, 
                                c.cliCorreo, 
                                c.cliDni,
                                SUM(d.detVenCantidad * d.detVenPrecio) AS total, 
                                v.venFechaRegis,
                                e.estVenNombre AS Estado,
                                e.estVenId AS estadoId
                              FROM detalleventa d 
                              INNER JOIN ventas v ON d.venId = v.venId 
                              INNER JOIN cliente c ON v.cliId = c.cliId 
                              INNER JOIN estadoventa e ON v.estVenId = e.estVenId";

                            // Agregar condición para filtrar por estado
                            if ($filter_marca != '') {
                                $query .= " WHERE v.estVenId = '$filter_marca'";
                            }

                            $query .= " GROUP BY v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, c.cliDni, v.venFechaRegis, e.estVenNombre, e.estVenId ORDER BY v.venFechaRegis DESC";
                            $query .= " LIMIT $offset, $registros_por_pagina";

                            $result = mysqli_query($con, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Determinar la clase CSS según el estado
                                $estadoClase = '';
                                switch ($row['estadoId']) {
                                    case '1': // Suponiendo que 1 es 'Pendiente'
                                        $estadoClase = 'estado-pendiente';
                                        break;
                                    case '2': // Suponiendo que 2 es 'Completada'
                                        $estadoClase = 'estado-completada';
                                        break;
                                    case '3': // Suponiendo que 3 es 'Cancelada'
                                        $estadoClase = 'estado-cancelada';
                                        break;
                                }
                                // Formatear la fecha de registro en formato día/mes/año
                                $fecha_registro = date('d/m/Y', strtotime($row['venFechaRegis']));
                                echo "
                                <tr>
                                    <td>{$row['venId']}</td>
                                    <td>{$row['cliNombre']}</td>
                                    <td>{$row['Apellidos']}</td>
                                    <td><div class='$estadoClase'>{$row['Estado']}</div></td>
                                    <td>{$row['cliCorreo']}</td>
                                    <td>{$row['cliDni']}</td>
                                    <td>{$row['total']}</td>
                                    <td>{$fecha_registro}</td>
                                    <td>
                                        <a href='ver-detalles-venta.php?venId={$row['venId']}' class='btn btn-info me-2'>
                                          <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'><g fill='none' stroke='currentColor' stroke-width='1.5'><path stroke-linecap='round' d='M9 4.46A9.8 9.8 0 0 1 12 4c4.182 0 7.028 2.5 8.725 4.704C21.575 9.81 22 10.361 22 12c0 1.64-.425 2.191-1.275 3.296C19.028 17.5 16.182 20 12 20s-7.028-2.5-8.725-4.704C2.425 14.192 2 13.639 2 12c0-1.64.425-2.191 1.275-3.296A14.5 14.5 0 0 1 5 6.821'/><path d='M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0Z'/></g></svg>
                                        </a>
                                        <a href='reporte-detalles-ventas.php?venId={$row['venId']}' target='_blank' class='btn btn-info'>
                                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'><g fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='1'><path stroke-width='1.5' d='M7.792 21.25h8.416a3.5 3.5 0 0 0 3.5-3.5v-5.53a3.5 3.5 0 0 0-1.024-2.475l-5.969-5.97A3.5 3.5 0 0 0 10.24 2.75H7.792a3.5 3.5 0 0 0-3.5 3.5v11.5a3.5 3.5 0 0 0 3.5 3.5'/><path stroke-width='1.5' d='M11.688 3.11v5.66a2 2 0 0 0 2 2h5.662'/><path d='M7.25 16.5v-1m0 0v-2h1a1 1 0 0 1 1 1v0a1 1 0 0 1-1 1zm4 1v-3h.5a1.5 1.5 0 0 1 0 3zm4 0v-1.25m1.5-1.75h-1.5v1.75m0 0h1.5'/></g></svg>
                                        </a>
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

<script>
    function validarFechas() {
        // Obtener las fechas seleccionadas
        const fechaDesde = document.getElementById('fecha_desde').value;
        const fechaHasta = document.getElementById('fecha_hasta').value;

        // Convertir las fechas a objetos Date
        const desde = new Date(fechaDesde);
        const hasta = new Date(fechaHasta);

        // Validar que la fecha "desde" no sea mayor que la fecha "hasta"
        if (desde > hasta) {
            alert("La fecha 'Desde' no puede ser mayor que la fecha 'Hasta'.");
            return; 
        }

        cargarReporte('rango');
    }

    function cargarReporte(tipo) {
        console.log("Cargando reporte...");
    }

    function descargarPDF() {
        console.log("Generando PDF...");
}
</script>

<?php include "../../footer.php"; ?>