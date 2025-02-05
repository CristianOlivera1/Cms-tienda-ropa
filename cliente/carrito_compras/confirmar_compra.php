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
        $stock = $producto->stoId; // Asegúrate de que esto esté correcto
        $productoId = $producto->id; 
        $cantidad = $producto->cantidad; 
        $precioUnitario = $producto->precio; 
    
        $total = $cantidad * $precioUnitario; 
        $subtotal += $total;
    
        // Insertar detalle en detalle_ventas
        $stmt = $con->prepare("INSERT INTO detalleventa (venId, stoId, detVenCantidad, detVenPrecio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $ventaId, $stock, $cantidad, $precioUnitario); // Cambia productoId por stock
        $stmt->execute();
    }

    // Redirigir a WhatsApp con los detalles
    $mensaje = "Cliente ID: $cliId\nSubtotal: $subtotal\nEnvío: Gratis\nTotal: $subtotal";
    $whatsappUrl = "https://wa.link/uaj6pe?text=" . urlencode($mensaje);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    subtotal += producto.cantidad * producto.precio;
                });
            }

            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('total').textContent = subtotal.toFixed(2);
            productosHiddenInput.value = JSON.stringify(productos);
        });
    </script>
    <style>
        body {
            background: url('../recursos/img/welcome/fondo-portada.svg') no-repeat center center fixed;
            background-size: cover;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .btn-success {
            background-color:rgb(28, 3, 118);
            border-color:rgb(43, 2, 119);
            transition: background-color 0.2s, border-color 0.2s;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color:rgb(10, 10, 10);
        }
        h2, h4 {
            color:rgb(13, 13, 13);
        }
        h5 {
            color: #333;
        }
        table th, table td {
            text-align: center;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
        .table th {
            background-color:rgb(10, 10, 10);
            color: white;
        }
        .table td {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4">
            <h2 class="text-center"><i class="fas fa-shopping-cart"></i> Confirmar Venta</h2>
            <p class="text-center text-muted">Estimado  <?php echo $cliId; ?> gracias por su compra</p>
            
            <div class="mt-3">
                <h4 class="text-center">Productos:</h4>
                <table class="table table-striped">
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
            
            <div class="text-end">
                <p class="h5">Subtotal: S/. <span id="subtotal">0.00</span></p>
                <p class="h6">Envío: Gratis</p>
                <h5>Total: S/. <span id="total">0.00</span></h5>
            </div>

            <form method="POST" action="">
                <input type="hidden" id="productos" name="productos">
                <button type="submit" class="btn btn-success w-100"><i class="fas fa-check"></i> Confirmar Compra</button>
            </form>
        </div>
    </div>
</body>
</html>
