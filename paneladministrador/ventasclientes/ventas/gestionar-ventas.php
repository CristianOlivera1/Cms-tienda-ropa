<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

// Configuración de la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el término de búsqueda y filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';

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

// Consultas para obtener los datos necesarios
$queryCantidadVentas = "SELECT COUNT(*) AS cantidad FROM ventas v inner join detalleventa dv on dv.venId=v.venId";
$resultCantidad = mysqli_query($con, $queryCantidadVentas);
$cantidadVentas = mysqli_fetch_assoc($resultCantidad)['cantidad'];

$queryTotalVentas = "SELECT SUM(detVenPrecio * detVenCantidad) AS total FROM detalleventa";
$resultTotal = mysqli_query($con, $queryTotalVentas);
$totalVentas = mysqli_fetch_assoc($resultTotal)['total'];

// Obtener el producto más frecuente
$queryProductoFrecuente = "
    SELECT p.proNombre 
    FROM detalleventa dv 
    INNER JOIN stock s ON dv.stoId = s.stoId 
    INNER JOIN producto p ON s.proId = p.proId 
    GROUP BY p.proId 
    ORDER BY SUM(dv.detVenCantidad) DESC 
    LIMIT 1";
$resultProducto = mysqli_query($con, $queryProductoFrecuente);
$productoFrecuente = ($resultProducto && mysqli_num_rows($resultProducto) > 0) ? mysqli_fetch_assoc($resultProducto)['proNombre'] : 'N/A';

$queryClienteFrecuente = "
    SELECT c.cliNombre 
    FROM ventas v 
    INNER JOIN cliente c ON v.cliId = c.cliId 
    GROUP BY v.cliId 
    ORDER BY COUNT(v.cliId) DESC 
    LIMIT 1";
$resultCliente = mysqli_query($con, $queryClienteFrecuente);
$clienteFrecuente = ($resultCliente && mysqli_num_rows($resultCliente) > 0) ? mysqli_fetch_assoc($resultCliente)['cliNombre'] : 'N/A';

    
// Consultas para obtener los datos de ventas por semana (desglosadas por días)
$queryVentasSemana = "
SELECT DATE(venFechaRegis) AS fecha, SUM(detVenCantidad * detVenPrecio) AS total
FROM detalleventa d
INNER JOIN ventas v ON d.venId = v.venId
WHERE venFechaRegis >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY fecha
ORDER BY fecha;";
$resultVentasSemana = mysqli_query($con, $queryVentasSemana);
$ventasPorSemana = [];
while ($row = mysqli_fetch_assoc($resultVentasSemana)) {
$ventasPorSemana[] = $row;
}

// Ventas por mes
$queryVentasMes = "
SELECT MONTH(venFechaRegis) AS mes, SUM(detVenCantidad * detVenPrecio) AS total
FROM detalleventa d
INNER JOIN ventas v ON d.venId = v.venId
GROUP BY mes
ORDER BY mes;";
$resultVentasMes = mysqli_query($con, $queryVentasMes);
$ventasPorMes = [];
while ($row = mysqli_fetch_assoc($resultVentasMes)) {
$ventasPorMes[] = $row;
}
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
            <div class="row">
  <div class="col-12">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header bg-primary text-white rounded-top-4 py-3">
        <h5 class="card-title mb-0 text-center">Reportes de Ventas</h5>
      </div>
      <div class="card-body">

        <!-- Métricas principales -->
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
              <div class="card-body">
                <div class="mb-3">
                  <i class="bi bi-cart-check-fill text-success fs-1"></i>
                </div>
                <h5 class="card-title">Cantidad de Ventas</h5>
                <p class="card-text display-6 fw-bold" id="cantidad-ventas">
                  <?php echo htmlspecialchars($cantidadVentas); ?>
                </p>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
              <div class="card-body">
                <div class="mb-3">
                  <i class="bi bi-currency-dollar text-primary fs-1"></i>
                </div>
                <h5 class="card-title">Total Ventas</h5>
                <p class="card-text display-6 fw-bold text-primary" id="total-ventas">
                  <?php echo '$' . number_format($totalVentas, 2); ?>
                </p>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100">
              <div class="card-body">
                <div class="mb-3">
                  <i class="bi bi-bag-heart-fill text-danger fs-1"></i>
                </div>
                <h5 class="card-title">Producto Más Vendido</h5>
                <p class="card-text display-6 fw-bold text-danger" id="producto-mas-vendido">
                  <?php echo htmlspecialchars($productoFrecuente); ?>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Métrica adicional -->
        <div class="row mt-4">
          <div class="col-md-4 mx-auto">
            <div class="card border-0 shadow-sm text-center h-100">
              <div class="card-body">
                <div class="mb-3">
                  <i class="bi bi-person-fill text-warning fs-1"></i>
                </div>
                <h5 class="card-title">Cliente Más Frecuente</h5>
                <p class="card-text display-6 fw-bold text-warning" id="cliente-frecuente">
                  <?php echo htmlspecialchars($clienteFrecuente); ?>
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Icons CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<!-- Custom CSS (Optional) -->
<style>
  .card {
    transition: transform 0.2s ease-in-out;
  }

  .card:hover {
    transform: scale(1.05);
  }

  .card-title {
    font-weight: 600;
  }

  .display-6 {
    font-size: 1.5rem;
  }
</style>

                    <style>
    .main-content {
        padding: 20px;
    }
    .chart-container {
        position: relative;
        margin: auto;
        max-width: 600px;
        margin-bottom: 40px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }
    h5 {
        text-align: center;
        color: #333;
    }
</style>


<div class="page-content">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0"><strong>Gráfico de Ventas</strong></h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-4">
                            <div class="btn-group" role="group" aria-label="Botones de gráfico">
                                <button class="btn btn-outline-primary active" id="btn-ventas-semana">Ventas por Semana</button>
                                <button class="btn btn-outline-primary" id="btn-ventas-mes">Ventas por Mes</button>
                            </div>
                        </div>

                        <div class="chart-container position-relative">
                            <canvas id="ventasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ventasChartCtx = document.getElementById('ventasChart').getContext('2d');

    // Datos obtenidos del servidor (ventas por semana y mes)
    const ventasPorSemana = <?php echo json_encode($ventasPorSemana); ?>;
    const ventasPorMes = <?php echo json_encode($ventasPorMes); ?>;

    // Función para generar datos dinámicos
    function generarDatosSemana() {
    const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    const totalSemana = Array(7).fill(0); // Inicializa el array con 0 para los 7 días de la semana

    const hoy = new Date(); // Obtén la fecha actual
    const diaActual = (hoy.getDay() + 6) % 7; // Ajuste para que 0 = Lunes, ..., 6 = Domingo

    for (let i = 0; i < 7; i++) {
        // Calcula la fecha correspondiente para cada día de la semana
        const fecha = new Date(hoy);
        fecha.setDate(hoy.getDate() - diaActual + i); // Ajusta para obtener la fecha del día correspondiente

        // Formatea la fecha en formato ISO (YYYY-MM-DD) para la comparación
        const fechaFormateada = fecha.toISOString().split('T')[0];

        // Busca si hay ventas para esta fecha
        const ventaDelDia = ventasPorSemana.find(row => row.fecha === fechaFormateada);

        // Llena el array de totales con el valor encontrado o 0
        totalSemana[i] = ventaDelDia ? ventaDelDia.total : 0;
    }

    return {
        labels: diasSemana,
        datasets: [{
            label: 'Ventas por Semana',
            data: totalSemana,
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            borderRadius: 8
        }]
    };
}


    function generarDatosMes() {
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        const totalMes = Array(12).fill(0);

        ventasPorMes.forEach(row => {
            totalMes[row.mes - 1] = row.total;
        });

        return {
            labels: meses,
            datasets: [{
                label: 'Ventas por Mes',
                data: totalMes,
                backgroundColor: 'rgba(153, 102, 255, 0.7)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                borderRadius: 8
            }]
        };
    }

    // Inicializar gráfico con ventas por semana
    let ventasChart = new Chart(ventasChartCtx, {
        type: 'bar',
        data: generarDatosSemana(),
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Total Ganancias' },
                    ticks: {
                        callback: value => `$${value}` // Formato de moneda
                    }
                },
                x: {
                    title: { display: true, text: 'Período' }
                }
            }
        }
    });

    // Event Listeners para alternar los datos
    document.getElementById('btn-ventas-semana').addEventListener('click', () => {
        ventasChart.data = generarDatosSemana();
        ventasChart.update();
        toggleActiveButton('btn-ventas-semana');
    });

    document.getElementById('btn-ventas-mes').addEventListener('click', () => {
        ventasChart.data = generarDatosMes();
        ventasChart.update();
        toggleActiveButton('btn-ventas-mes');
    });

    // Cambiar clase activa en los botones
    function toggleActiveButton(activeId) {
        document.querySelectorAll('.btn-group .btn').forEach(button => {
            button.classList.remove('active');
        });
        document.getElementById(activeId).classList.add('active');
    }

    
</script>

<style>
    .chart-container {
        height: 400px;
        position: relative;
    }

    .btn-group .btn.active {
        background-color: #0d6efd;
        color: #fff;
    }

    .btn-group .btn {
        transition: background-color 0.3s, color 0.3s;
    }

    .card {
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }
</style>
<div class="card-header">
    <h5 class="card-title mb-0">Lista de Ventas</h5>
</div>

<div class="card-body">
    <table class="table table-hover" id="detallesTable">
        <thead>
            <tr>
                <th>Código de venta</th>
                <th>Nombre del cliente</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Correo</th>
                <th>Precio Total</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta ajustada para obtener solo el ID y el total
            $query = "SELECT v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, SUM(d.detVenCantidad * d.detVenPrecio) AS total, v.venFechaRegis FROM detalleventa d INNER JOIN ventas v ON d.venId = v.venId INNER JOIN cliente c ON v.cliId = c.cliId GROUP BY v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, v.venFechaRegis;";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <tr id='detalle-{$row['venId']}'>
                    <td>{$row['venId']}</td>
                    <td>{$row['cliNombre']}</td>
                    <td>{$row['cliApellidoPaterno']}</td>
                    <td>{$row['cliApellidoMaterno']}</td>
                    <td>{$row['cliCorreo']}</td>
                    <td>{$row['total']}</td>
                    <td>{$row['venFechaRegis']}</td>
                    <td>
                        <a href='ver-detalles-venta.php?venId={$row['venId']}' class='btn btn-info'>
                            <i class='fa fa-eye'></i>
                        </a>
                    </td>
                    
                    
                    
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
$(document).ready(function() {
    // Funcionalidad de impresión
    $('#btnImprimir').click(function() {
        window.print(); // Llama a la función de impresión del navegador
    });

    $('.eliminar-btn').click(function() {
        var detVenId = $(this).data('id');
        var row = $(this).closest('tr');

        if (confirm('¿Estás seguro de que deseas eliminar este detalle de venta?')) {
            $.ajax({
                url: 'eliminar-detalle-venta.php?detVenId=' + detVenId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        row.remove();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Ocurrió un error al intentar eliminar el detalle de venta.');
                }
            });
        }
    });
});
</script>
<!-- Asegúrate de incluir Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<?php include "../../footer.php"; ?>