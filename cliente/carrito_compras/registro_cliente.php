<?php
// Incluir conexión a la base de datos
include '../coneccionbd.php';
include('../header.php');

// Procesar formulario al enviar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos del cliente desde el formulario
    $nombre = $con->real_escape_string($_POST['cliNombre']);
    $apePaterno = $con->real_escape_string($_POST['cliApellidoPaterno']);
    $apeMaterno = $con->real_escape_string($_POST['cliApellidoMaterno']);
    $dni = $con->real_escape_string($_POST['cliDni']);
    $correo = $con->real_escape_string($_POST['cliCorreo']);

    // Insertar datos del cliente en la base de datos
    $sql_cliente = "INSERT INTO cliente (cliNombre, cliApellidoPaterno, cliApellidoMaterno, cliDni, cliCorreo) 
                    VALUES ('$nombre', '$apePaterno', '$apeMaterno', '$dni', '$correo')";

    if ($con->query($sql_cliente) === TRUE) {
        // Obtener el ID del cliente recién insertado
        $id_cliente = $con->insert_id;

        // Crear una nueva venta asociada al cliente
        $sql_venta = "INSERT INTO ventas (cliId, venFechaRegis) VALUES ('$id_cliente', NOW())";
        if ($con->query($sql_venta) === TRUE) {
            // Obtener el ID de la venta recién creada
            $id_venta = $con->insert_id;

            // Procesar los datos del carrito desde localStorage
            $carrito = json_decode($_POST['carrito'], true);
            if (!empty($carrito)) {
                $total = 0;

                foreach ($carrito as $item) {
                    $nombre_producto = $con->real_escape_string($item['nombre']);
                    $precio_producto = floatval($item['precio']);
                    $cantidad_producto = intval($item['cantidad']);
                    $subtotal_producto = $precio_producto * $cantidad_producto;

                    // Insertar en detalle_venta
                    $sql_detalle = "INSERT INTO detalleventa (venId, stoId, detVenPrecio, detVenCantidad) 
                                    VALUES ('$id_venta', '$cantidad_producto', '$subtotal_producto', '$cantidad_producto')";

                    if ($con->query($sql_detalle) === TRUE) {
                        // Sumar al total de la venta
                        $total += $subtotal_producto;
                    } else {
                        echo "Error al insertar detalle de venta: " . $con->error;
                    }
                }

                // Actualizar el total de la venta
                $sql_actualizar_total = "UPDATE ventas SET total = '$total' WHERE venId = '$id_venta'";
                if ($con->query($sql_actualizar_total) === TRUE) {
                    echo "Compra registrada correctamente.";
                } else {
                    echo "Error al actualizar el total de la venta: " . $con->error;
                }
            } else {
                echo "Error: El carrito está vacío.";
            }
        } else {
            echo "Error al registrar la venta: " . $con->error;
        }
    } else {
        echo "Error al registrar cliente: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Compra</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <form action="" method="POST" id="formulario-compra">
            <div class="row">
                <!-- Contacto -->
                <div class="col-md-8">
                    <h4>Contacto:</h4>
                    <div class="mb-3">
                        <label for="cliCorreo" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="cliCorreo" name="cliCorreo" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="ofertas" name="ofertas">
                        <label class="form-check-label" for="ofertas">Enviarme novedades y ofertas por correo electrónico</label>
                    </div>

                    <!-- Nombre -->
                    <h4>Introdusca sus datos:</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cliNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="cliNombre" name="cliNombre" placeholder="Ingresar nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cliApellidoPaterno" class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" id="cliApellidoPaterno" name="cliApellidoPaterno" placeholder="Apellido paterno" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cliApellidoMaterno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="cliApellidoMaterno" name="cliApellidoMaterno" placeholder="Apellido materno" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cliFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="cliFechaNacimiento" name="cliFechaNacimiento" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cliDni" class="form-label">DNI</label>
                        <input type="text" class="form-control" name="cliDni" placeholder="DNI" required maxlength="8" pattern="\d{8}">
                    </div>
                </div>

                <!-- Resumen -->
                <div class="col-md-4">
                    <h4>Resumen:</h4>
                    <ul class="list-group" id="resumen-lista">
                        <!-- Aquí se llenarán los datos dinámicamente -->
                    </ul>
                </div>

                <input type="hidden" name="carrito" id="carrito-input">
            </div>

            <!-- Botón Confirmar -->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </div>
        </form>
    </div>

    <!-- Script para manejar localStorage -->
    <script>
        const resumenLista = document.getElementById("resumen-lista");
        const carritoInput = document.getElementById("carrito-input");

        const datosResumen = JSON.parse(localStorage.getItem("carrito")) || [];

        if (datosResumen.length > 0) {
            let subtotal = 0;

            datosResumen.forEach(item => {
                const li = document.createElement("li");
                li.className = "list-group-item d-flex justify-content-between align-items-center";
                li.innerHTML = `
                    ${item.nombre}
                    <span>S/. ${item.precio.toFixed(2)}</span>
                `;
                resumenLista.appendChild(li);

                subtotal += item.precio * item.cantidad;
            });

            const subtotalLi = document.createElement("li");
            subtotalLi.className = "list-group-item d-flex justify-content-between align-items-center";
            subtotalLi.innerHTML = `
                Subtotal
                <span>S/. ${subtotal.toFixed(2)}</span>
            `;
            resumenLista.appendChild(subtotalLi);
        } else {
            const li = document.createElement("li");
            li.className = "list-group-item";
            li.textContent = "No hay productos en el carrito.";
            resumenLista.appendChild(li);
        }

        carritoInput.value = JSON.stringify(datosResumen);
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('../footer.php'); ?>
</body>
</html>