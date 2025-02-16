<?php
include "../../header.php";
include "../../sidebar.php";

// Obtener el venId de la URL
$venId = $_GET['venId'] ?? '';
if (empty($venId)) {
    die("Error: venId no se ha proporcionado.");
}

// Obtener la fecha de la venta
$queryFecha = "SELECT venFechaRegis, venId FROM ventas WHERE venId = '$venId'";
$resultFecha = mysqli_query($con, $queryFecha);
if (!$resultFecha) {
    die("Error en la consulta: " . mysqli_error($con));
}
$fechaVenta = mysqli_fetch_assoc($resultFecha);

// Obtener el nombre del cliente
$queryCliente = "SELECT CONCAT(cliNombre, ' ', cliApellidoPaterno, ' ', cliApellidoMaterno) AS NombreCompleto
                    FROM cliente 
                    WHERE cliId = (SELECT cliId FROM ventas WHERE venId = '$venId')";
$resultCliente = mysqli_query($con, $queryCliente);
$nombrecliente = mysqli_fetch_assoc($resultCliente);

// Obtener DNI del cliente
$queryDni = "SELECT cliDni, cliCorreo FROM cliente WHERE cliId = (SELECT cliId FROM ventas WHERE venId = '$venId')";
$resultDni = mysqli_query($con, $queryDni);
$dni = mysqli_fetch_assoc($resultDni);

// Consulta para obtener el total de la venta
$queryTotal = "SELECT SUM(detVenPrecio * detVenCantidad) AS total FROM detalleventa WHERE venId = '$venId'";
$resultTotal = mysqli_query($con, $queryTotal);
$totalVenta = mysqli_fetch_assoc($resultTotal);

// Obtener el id de un detalle de la venta
$queryIdDetalle = "SELECT detVenId FROM detalleventa WHERE venId = '$venId'";
$resultIdDetalle = mysqli_query($con, $queryIdDetalle);
$idDetalle = mysqli_fetch_assoc($resultIdDetalle);

// Obtener el id de un producto de la venta 
$queryIdDetVen = "SELECT detVenId FROM detalleventa WHERE venId = '$venId'";
$resultIddetven = mysqli_query($con, $queryIdDetVen);
$idDetVen = mysqli_fetch_assoc($resultIddetven);

// Consulta para obtener los productos de la venta
$queryProductos = "SELECT p.proNombre, c.colNombre, t.talNombre, SUM(d.detVenCantidad) AS totalCantidad,
    d.detVenPrecio, SUM(d.detVenCantidad * d.detVenPrecio) AS totalPrecio, d.detVenId
FROM ventas v 
INNER JOIN detalleventa d ON v.venId = d.venId 
INNER JOIN stock s ON s.stoId = d.stoId 
INNER JOIN talla t ON s.talId = t.talId 
INNER JOIN color c ON c.colId = s.colId 
INNER JOIN producto p ON p.proId = s.proId
WHERE v.venId = '$venId' 
GROUP BY p.proNombre, c.colNombre, t.talNombre, d.detVenPrecio;";

$resultProductos = mysqli_query($con, $queryProductos);
$productos = mysqli_fetch_all($resultProductos, MYSQLI_ASSOC);  // Cambiado a fetch_all para obtener todos los productos

// Consulta para obtener los detalles de la venta
$query = "SELECT d.*, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, c.cliDni 
          FROM detalleventa d 
          INNER JOIN ventas v ON d.venId = v.venId 
          INNER JOIN cliente c ON v.cliId = c.cliId 
          WHERE v.venId = '$venId'";

$result = mysqli_query($con, $query);
$venta = mysqli_fetch_assoc($result);
?>


<?php
include "../../footer.php";
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Detalles de la Venta</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="gestionar-ventas.php">Inicio</a></li>
                                <li class="breadcrumb-item active">Detalles de la Venta</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="container">
                        <div class="row justify-content-evenly my-3">
                            <div class="col-5">
                                <h3 class="card-title mb-0">Codigo de Venta: <?php echo htmlspecialchars($fechaVenta['venId']); ?></h3>
                                <h3 class="card-title mb-0">Nombre de Cliente: <?php echo htmlspecialchars($nombrecliente['NombreCompleto']); ?></h3>
                                <h3 class="card-title mb-0">DNI: <?php echo htmlspecialchars($dni['cliDni']); ?></h3> 
                            </div>
                            <div class="col-5">
                                <h3 class="card-title mb-0">Correo: <?php echo htmlspecialchars($dni['cliCorreo']);?></h3> 
                                <h3 class="card-title mb-0">Fecha de Registro: <?php echo htmlspecialchars($fechaVenta['venFechaRegis']);?></h3> 
                            </div>
                        </div>                        
                        <table class="table table-hover my-3">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Producto</th>
                                    <th>Talla</th>
                                    <th>Color</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($productos): 
                                $contador = 1; 
                                foreach ($productos as $producto): ?>
                                    <tr>
                                        <td><?php echo $contador++; ?></td>
                                        <td><?php echo htmlspecialchars($producto['proNombre']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['talNombre']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['colNombre']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['totalCantidad']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['detVenPrecio']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['totalPrecio']); ?></td> 
                                    </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No se encontraron detalles para esta venta.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Total de la Venta:</th>
                                    <th class="text-end col-9"><?php echo htmlspecialchars($totalVenta['total']); ?></th>
                                </tr>
                            </thead>
                        </table>
                        <div class="d-flex justify-content-end mb-3">
                        <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#cancelarModal">
        Cancelar
    </button> 
    
<button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#confirmarModal">
        Confirmar
    </button> 
</div>
                            <a style="height: 38px;" href="reporte-detalles-ventas.php?venId=<?php echo htmlspecialchars($fechaVenta['venId']); ?>" target="_blank" class="btn btn-info me-2"> <!-- Cambiado a enlace -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25">
                                    <path fill="#909090" d="m24.1 2.072l5.564 5.8v22.056H8.879V30h20.856V7.945z"/>
                                    <path fill="#f4f4f4" d="M24.031 2H8.808v27.928h20.856V7.873z"/>
                                    <path fill="#7a7b7c" d="M8.655 3.5h-6.39v6.827h20.1V3.5z"/>
                                    <path fill="#dd2025" d="M22.472 10.211H2.395V3.379h20.077z"/>
                                    <path fill="#464648" d="M9.052 4.534H7.745v4.8h1.028V7.715L9 7.728a2 2 0 0 0 .647-.117a1.4 1.4 0 0 0 .493-.291a1.2 1.2 0 0 0 .335-.454a2.1 2.1 0 0 0 .105-.908a2.2 2.2 0 0 0-.114-.644a1.17 1.17 0 0 0-.687-.65a2 2 0 0 0-.409-.104a2 2 0 0 0-.319-.026m-.189 2.294h-.089v-1.48h.193a.57.57 0 0 1 .459.181a.92.92 0 0 1 .183.558c0 .246 0 .469-.222.626a.94.94 0 0 1-.524.114m3.671-2.306c-.111 0-.219.008-.295.011L12 4.538h-.78v4.8h.918a2.7 2.7 0 0 0 1.028-.175a1.7 1.7 0 0 0 .68-.491a1.9 1.9 0 0 0 .373-.749a3.7 3.7 0 0 0 .114-.949a4.4 4.4 0 0 0-.087-1.127a1.8 1.8 0 0 0-.4-.733a1.6 1.6 0 0 0-.535-.4a2.4 2.4 0 0 0-.549-.178a1.3 1.3 0 0 0-.228-.017m-.182 3.937h-.1V5.392h.013a1.06 1.06 0 0 1 .6.107a1.2 1.2 0 0 1 .324.4a1.3 1.3 0 0 1 .142.526c.009.22 0 .4 0 .549a3 3 0 0 1-.033.513a1.8 1.8 0 0 1-.169.5a1.1 1.1 0 0 1-.363.36a.67.67 0 0 1-.416.106m5.08-3.915H15v4.8h1.028V7.434h1.3v-.892h-1.3V5.43h1.4v-.892"/>
                                    <path fill="#dd2025" d="M21.781 20.255s3.188-.578 3.188.511s-1.975.646-3.188-.511m-2.357.083a7.5 7.5 0 0 0-1.473.489l.4-.9c.4-.9.815-2.127.815-2.127a14 14 0 0 0 1.658 2.252a13 13 0 0 0-1.4.288Zm-1.262-6.5c0-.949.307-1.208.546-1.208s.508.115.517.939a10.8 10.8 0 0 1-.517 2.434a4.4 4.4 0 0 1-.547-2.162Zm-4.649 10.516c-.978-.585 2.051-2.386 2.6-2.444c-.003.001-1.576 3.056-2.6 2.444M25.9 20.895c-.01-.1-.1-1.207-2.07-1.16a14 14 0 0 0-2.453.173a12.5 12.5 0 0 1-2.012-2.655a11.8 11.8 0 0 0 .623-3.1c-.029-1.2-.316-1.888-1.236-1.878s-1.054.815-.933 2.013a9.3 9.3 0 0 0 .665 2.338s-.425 1.323-.987 2.639s-.946 2.006-.946 2.006a9.6 9.6 0 0 0-2.725 1.4c-.824.767-1.159 1.356-.725 1.945c.374.508 1.683.623 2.853-.91a23 23 0 0 0 1.7-2.492s1.784-.489 2.339-.623s1.226-.24 1.226-.24s1.629 1.639 3.2 1.581s1.495-.939 1.485-1.035"/>
                                </svg>
                                Imprimir
                            </a>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
    <link rel="icon" type="image/x-icon" href="/paneladministrador/recursos/images/favicon/favicon.ico">
</div>
<!-- Modal de Confirmación para Confirmar Venta -->
<div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarModalLabel">Confirmar Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas confirmar esta venta?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnConfirmar" data-id="<?php echo htmlspecialchars($fechaVenta['venId']); ?>">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Cancelar Venta -->
<div class="modal fade" id="cancelarModal" tabindex="-1" aria-labelledby="cancelarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelarModalLabel">Cancelar Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas cancelar esta venta?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnCancelar" data-id="<?php echo htmlspecialchars($fechaVenta['venId']); ?>">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('btnConfirmar').addEventListener('click', function() {
    const venId = this.getAttribute('data-id');
    // Primero, actualizar el stock
    fetch('actualizar_stock.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ venId: venId }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Luego, cambiar el estado a "Confirmado"
            return fetch('actualizar-estado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ venId: venId, estado: 'Confirmado' }),
            });
        } else {
            throw new Error('Error al actualizar el stock: ' + data.message);
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirigir o actualizar la página
            window.location.href = 'gestionar-ventas.php';
        } else {
            alert('Error al cambiar el estado: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});

document.getElementById('btnCancelar').addEventListener('click', function() {
    const venId = this.getAttribute('data-id');
    // Aquí puedes hacer la petición para cambiar el estado a "Cancelado"
    fetch('actualizar-estado.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ venId: venId, estado: 'Cancelado' }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirigir o actualizar la página
            window.location.href = 'gestionar-ventas.php';
        } else {
            alert('Error al cambiar el estado: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>
<?php include "../../footer.php"; ?>


