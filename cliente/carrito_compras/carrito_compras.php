<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resumen de Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body class="bg-light">

    <div class="container mt-5 pt-4">
        <h3 class="text-center mb-4 pb-2 border-bottom">Resumen de mi Pedido</h3>

        <div class="table-responsive bg-white shadow p-3 rounded">
            <table class="table table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th scope="col">Detalle</th>
                        <th scope="col">Producto</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="cuerpoCarrito">
                    <!-- Productos generados por JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="mensajeCarrito" class="alert alert-warning text-center mt-3" style="display: none;">
            Tu carrito está vacío.
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="../producto/producto.php" class="btn btn-outline-dark">
                <i class="bi bi-cart-plus"></i> Seguir Comprando
            </a>
            <a href="registro_cliente.php" class="btn btn-success">
                Solicitar Pedido <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

    <script>
        function cargarCarrito() {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            let cuerpoCarrito = document.getElementById('cuerpoCarrito');
            let mensajeCarrito = document.getElementById('mensajeCarrito');
            cuerpoCarrito.innerHTML = '';

            if (carrito.length === 0) {
                mensajeCarrito.style.display = 'block';
            } else {
                mensajeCarrito.style.display = 'none';
                let total = 0;

                carrito.forEach((producto, index) => {
                    total += producto.precio * producto.cantidad;
                    cuerpoCarrito.innerHTML += `
                        <tr>
                            <td class="text-center"><img src="../../paneladministrador/recursos/uploads/producto/${producto.img}" class="rounded" style="width: 80px;"></td>
                            <td>${producto.nombre}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${index}, -1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <span class="mx-2">${producto.cantidad}</span>
                                <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${index}, 1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </td>
                            <td class="text-end"><strong>$</strong>${producto.precio.toFixed(2)}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger" onclick="eliminarDelCarrito(${index})">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                cuerpoCarrito.innerHTML += `
                    <tr class="table-secondary">
                        <td colspan="3" class="text-end"><strong>Total a Pagar:</strong></td>
                        <td class="text-end"><strong>$${total.toFixed(2)}</strong></td>
                        <td></td>
                    </tr>
                `;
            }
        }

        function cambiarCantidad(index, cambio) {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito[index].cantidad += cambio;
            if (carrito[index].cantidad <= 0) eliminarDelCarrito(index);
            else {
                localStorage.setItem('carrito', JSON.stringify(carrito));
                cargarCarrito();
            }
        }

        function eliminarDelCarrito(index) {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito.splice(index, 1);
            localStorage.setItem('carrito', JSON.stringify(carrito));
            cargarCarrito();
        }

        document.addEventListener('DOMContentLoaded', cargarCarrito);
    </script>

</body>

</html>
