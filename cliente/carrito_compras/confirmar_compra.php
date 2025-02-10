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
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #333;
            font-weight: bold;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-confirmar {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }

        .btn-confirmar:hover {
            background-color: #218838;
        }

        .btn-cancelar {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }

        .btn-cancelar:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5 pt-4">
        <div class="card shadow p-4">
            <h3 class="text-center mb-4">Resumen de mi Pedido</h3>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">Detalle</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Color</th>
                            <th scope="col">Talla</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Precio Unitario</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoCarrito">
                        <!-- Los productos se cargarán aquí -->
                    </tbody>
                </table>
            </div>

            <div id="mensajeCarrito" class="alert alert-warning text-center mt-3" style="display: none;">
                Tu carrito está vacío.
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="../index.php" class="btn btn-cancelar">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button class="btn btn-confirmar" onclick="confirmarCompra()">
                    <i class="bi bi-check-circle"></i> Confirmar Compra
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para confirmación de compra -->
    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-labelledby="modalConfirmarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmarLabel">Confirmar Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas confirmar la compra?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-confirmar" onclick="insertarDetalleVenta()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let ventaId; // Variable para almacenar el ID de la venta

    function cargarCarrito() {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        let cuerpoCarrito = document.getElementById('cuerpoCarrito');
        let mensajeCarrito = document.getElementById('mensajeCarrito');
        cuerpoCarrito.innerHTML = '';

        if (carrito.length === 0) {
            mensajeCarrito.style.display = 'block';
        } else {
            mensajeCarrito.style.display = 'none';
            let totalGeneral = 0;

            carrito.forEach((producto) => {
                let totalProducto = producto.price * producto.quantity; // Calcular el total por producto
                totalGeneral += totalProducto; // Sumar al total general
                cuerpoCarrito.innerHTML += `
                    <tr>
                        <td class="text-center"><img src="../../paneladministrador/recursos/uploads/producto/${producto.image}" class="rounded" style="width: 80px;"></td>
                        <td>${producto.name}</td>
                        <td>${producto.color}</td>
                        <td>${producto.size}</td>
                        <td class="text-center">${producto.quantity}</td>
                        <td class="text-end"><strong>S/.</strong>${producto.price.toFixed(2)}</td>
                        <td class="text-end"><strong>S/.${totalProducto.toFixed(2)}</strong></td>
                    </tr>
                `;
            });

            cuerpoCarrito.innerHTML += `
                <tr class="table-secondary">
                    <td colspan="5" class="text-end"><strong>Total a Pagar:</strong></td>
                    <td class="text-end" colspan="2"><strong>S/.${totalGeneral.toFixed(2)}</strong></td>
                </tr>
            `;
        }
    }

    function insertarDetalleVenta() {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        
        // Obtener cliId y venId de la URL
        const params = new URLSearchParams(window.location.search);
        let cliId = params.get('cliId'); // Obtener cliId de la URL
        let ventaId = params.get('venId'); // Obtener venId de la URL

        console.log('Carrito:', carrito);
        console.log('cliId:', cliId);
        console.log('ventaId:', ventaId);

        // Verificar que el carrito no esté vacío y que cada producto tenga un stoId
        if (carrito.length === 0 || !carrito.every(producto => producto.stoId)) {
            alert('El carrito está vacío o hay productos sin ID de stock.');
            return;
        }

        // Enviar los datos al backend para insertar en detalleVenta
        fetch('insertar_detalleventa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ carrito, ventaId, cliId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Construir el mensaje para WhatsApp
                let mensaje = '¡Hola! He realizado un pedido:\n\n';
                carrito.forEach(producto => {
                    let totalProducto = (producto.price * producto.quantity).toFixed(2);
                    mensaje += `Producto: ${producto.name}\nColor: ${producto.color}\nTalla: ${producto.size}\nCantidad: ${producto.quantity}\nPrecio Unitario: S/.${producto.price.toFixed(2)}\nTotal: S/.${totalProducto}\n\n`;
                });
                let totalGeneral = carrito.reduce((total, producto) => total + (producto.price * producto.quantity), 0).toFixed(2);
                mensaje += `Total a Pagar: S/.${totalGeneral}\n\nGracias por su compra!`;

                // Codificar el mensaje para la URL
                let mensajeCodificado = encodeURIComponent(mensaje);
                let numeroWhatsApp = '+51991865886'; // Reemplaza con el número de WhatsApp deseado
                let urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${mensajeCodificado}`;

                // Redirigir a WhatsApp
                window.open(urlWhatsApp, '_blank');

                // Limpiar el carrito después de la inserción
                localStorage.removeItem('carrito'); // Limpiar el carrito
                window.location.href = '../producto/producto.php'; // Redirigir a la página de productos
            } else {
                alert('Error al confirmar la compra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error :', error);
        });
    }

    function confirmarCompra() {
        // Mostrar el modal de confirmación
        let modal = new bootstrap.Modal(document.getElementById('modalConfirmar'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', cargarCarrito);
</script>