<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resumen de Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: url('../recursos/img/welcome/fondo-portada.svg') no-repeat center center fixed;
            background-size: cover;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
        }

        h3 {
            color: #007bff; /* Color del título */
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5 pt-4">
        <div class="card shadow p-3">
            <h3 class="text-center mb-4 pb-2 border-bottom" style="color: black;">Resumen de mi Pedido</h3>
            <br>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th scope="col">Detalle</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Color</th>
                            <th scope="col">Talla</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Precio Unitario</th>
                            <th scope="col">Total</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoCarrito">
                        
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
                <a href="registro_cliente.php" class="btn btn-success" id="btnSolicitarPedido" style="display: none;">
                    Solicitar Pedido <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Modal para confirmación de eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" 
                    -bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este producto del carrito?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productoAEliminarIndex = -1;

        function cargarCarrito() {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            let cuerpoCarrito = document.getElementById('cuerpoCarrito');
            let mensajeCarrito = document.getElementById('mensajeCarrito');
            let btnSolicitarPedido = document.getElementById('btnSolicitarPedido');
            cuerpoCarrito.innerHTML = '';

            if (carrito.length === 0) {
                mensajeCarrito.style.display = 'block';
                btnSolicitarPedido.style.display = 'none'; // Ocultar el botón si el carrito está vacío
            } else {
                mensajeCarrito.style.display = 'none';
                btnSolicitarPedido.style.display = 'inline-block'; // Mostrar el botón si hay productos en el carrito
                let totalGeneral = 0;

                carrito.forEach((producto, index) => {
                    let totalProducto = (producto.price || 0) * producto.quantity; // Calcular el total por producto
                    totalGeneral += totalProducto; // Sumar al total general
                    cuerpoCarrito.innerHTML += `
                        <tr>
                            <td class="text-center"><img src="../../paneladministrador/recursos/uploads/producto/${producto.image}" class="rounded" style="width: 80px;"></td>
                            <td>${producto.name}</td>
                            <td>${producto.color}</td>
                            <td>${producto.size}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${index}, -1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <span class="mx-2">${producto.quantity}</span>
                                <button class="btn btn-outline-secondary btn-sm" onclick="cambiarCantidad(${index}, 1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </td>
                            <td class="text-end"><strong>S/.</strong>${(producto.price ? producto.price.toFixed(2) : '0.00')}</td>
                            <td class="text-end"><strong>S/.${totalProducto.toFixed(2)}</strong></td> <!-- Total por producto -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger" onclick="abrirModalEliminar(${index})">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                cuerpoCarrito.innerHTML += `
                    <tr class="table-secondary">
                        <td colspan="6" class="text-end"><strong>Total a Pagar:</strong></td>
                        <td class="text-end"><strong>S/.${totalGeneral.toFixed(2)}</strong></td>
                        <td></td>
                    </tr>
                `;
            }
        }

        function cambiarCantidad(index, cambio) {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            let nuevaCantidad = parseInt(carrito[index].quantity, 10) + cambio; // Asegúrate de que sea un número

            // Asegurarse de que la cantidad no sea menor que 1
            if (nuevaCantidad < 1) {
                abrirModalEliminar(index);
            } else {
                carrito[index].quantity = nuevaCantidad; // Actualizar la cantidad
                localStorage.setItem('carrito', JSON.stringify(carrito));
                cargarCarrito(); // Recargar el carrito para reflejar los cambios
            }
        }

        function abrirModalEliminar(index) {
            productoAEliminarIndex = index;
            let modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }

        document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
            eliminarDelCarrito(productoAEliminarIndex);
            let modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminar'));
            modal.hide();
        });

        function eliminarDelCarrito(index) {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito.splice(index, 1);
            localStorage.setItem('carrito', JSON.stringify(carrito));
            cargarCarrito();
        }

        document.addEventListener('DOMContentLoaded', cargarCarrito);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>