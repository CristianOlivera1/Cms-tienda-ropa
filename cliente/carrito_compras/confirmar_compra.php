<?php
include '../coneccionbd.php';

// Capturar cliId enviado
if (isset($_GET['cliId']) && is_numeric($_GET['cliId'])) {
    $cliId = (int) $_GET['cliId'];
} else {
    die("No se proporcionó un cliId válido.");
}

$subtotal = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los productos del POST
    $productos = json_decode($_POST['productos']);
    
    // Registrar la venta en la tabla ventas
    $stmt = $con->prepare("INSERT INTO ventas (cliId) VALUES (?)");
    $stmt->bind_param("i", $cliId);
    $stmt->execute();
    $ventaId = $stmt->insert_id; // Obtener el ID de la venta

    // Registrar los detalles de la venta
    foreach ($productos as $producto) {
        $productoId = $producto->id; // Suponiendo que cada producto tiene un ID
        $cantidad = $producto->cantidad; // Suponiendo que cada producto tiene una cantidad
        $precioUnitario = $producto->precio; // Suponiendo que cada producto tiene un precio

        $total = $cantidad * $precioUnitario; // Calcular el total del producto
        $subtotal += $total; // Sumar al subtotal

        // Insertar detalle en detalle_ventas
        $stmt = $con->prepare("INSERT INTO detalleventa (venId, stoId, detVenCantidad, detVenPrecio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $ventaId, $stoId, $cantidad, $precioUnitario);
        $stmt->execute();
    }

    // Redirigir a WhatsApp con los detalles
    // Crear el mensaje
$mensaje = "Cliente ID: $cliId\nSubtotal: $subtotal\nEnvío: Gratis\nTotal: $subtotal";

// URL de WhatsApp con el número específico
$whatsappUrl = "https://wa.link/uaj6pe?text=" . urlencode($mensaje);

// Redirigir a la URL de WhatsApp
header("Location: $whatsappUrl");
exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recuperar productos del localStorage
            const productos = JSON.parse(localStorage.getItem('carrito')) || [];
            const productosList = document.getElementById('productos-list');
            const productosHiddenInput = document.getElementById('productos');

            let subtotal = 0;

            if (productos.length === 0) {
                productosList.innerHTML = "<tr><td colspan='4'>No hay productos en el carrito.</td></tr>";
            } else {
                productos.forEach(producto => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${producto.nombre}</td>
                        <td>${producto.cantidad}</td>
                        <td>S/. ${producto.precio.toFixed(2)}</td>
                        <td>S/. ${(producto.cantidad * producto.precio).toFixed(2)}</td>
                    `;
                    productosList.appendChild(tr);

                    // Calcular el total
                    subtotal += producto.cantidad * producto.precio;
                });
            }

            // Mostrar subtotal en la página
            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('total').textContent = subtotal.toFixed(2);

            // Guardar productos en un input hidden para enviar al servidor
            productosHiddenInput.value = JSON.stringify(productos);
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Confirmar Venta</h2>
        <p class="text-center">Cliente ID: <?php echo $cliId; ?></p>
        
        <div class="mt-3">
            <h4>Productos:</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre del Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="productos-list">
                    <!-- Los productos se agregarán aquí -->
                </tbody>
            </table>
        </div>
        
        <p>Subtotal: S/. <span id="subtotal">0.00</span></p>
        <p>Envío: Gratis</p>
        <h5>Total: S/. <span id="total">0.00</span></h5>
        
        <form method="POST" action="">
            <input type="hidden" id="productos" name="productos">
            <button type="submit" class="btn btn-success w-100">Confirmar Compra</button>
        </form>
    </div>
</body>
</html>
