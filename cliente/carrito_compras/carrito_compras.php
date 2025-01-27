<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/bootstrap4/bootstrap.min.css">
</head>

<body>
        <div class="super_container mt-5 pt-5">
        <?php
        include('modalEliminarProduct.php');
        //include('funciones_tienda.php');
        include('../header.php');
        ?>

        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center mb-5" style="border-bottom: solid 1px #ebebeb;">
                        Resumen de mi Pedido
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-striped" id="tablaCarrito">
                            <thead id="theadTableSpecial">
                                <tr>
                                    <th scope="col">Detalle</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-right">Precio</th>
                                    <th class="text-right">Acción </th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoCarrito">
                                <!-- Los productos se agregarán aquí mediante JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col mb-2 mt-5">
                    <div class="row justify-content-md-center">
                        <div class="col-md-6 mb-4">
                            <a href="../producto/producto.php" class="btn btn-block btn_raza">
                                <i class="bi bi-cart-plus"></i>
                                Continuar Comprando
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="registro_cliente.php" class="btn btn-block btn-success">
                                Solicitar Pedido
                                <i class="bi bi-arrow-right-circle"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div id="mensajeCarrito" class="alert alert-warning" style="display: none;">
                        Tu carrito está vacío.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('../footer.php'); ?>
    

    <script>
        // Función para cargar el carrito desde localStorage
        function cargarCarrito() {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            let cuerpoCarrito = document.getElementById('cuerpoCarrito');
            let mensajeCarrito = document.getElementById('mensajeCarrito');
            cuerpoCarrito.innerHTML = ''; // Limpiar el cuerpo de la tabla

            if (carrito.length === 0) {
                mensajeCarrito.style.display = 'block'; // Mostrar mensaje de carrito vacío
            } else {
                mensajeCarrito.style.display = 'none'; // Ocultar mensaje de carrito vacío
                let total = 0;

                carrito.forEach((producto, index) => {
                    total += producto.precio * producto.cantidad; // Calcular total
                    cuerpoCarrito.innerHTML += `
                        <tr id="resp${producto.id}">
                            <td>
                                <img src="../../paneladministrador/recursos/uploads/producto/${producto.img}" alt="Foto_Producto" style="width: 100px;">
                            </td>
                            <td>${producto.nombre}</td>
                            <td>
                                <div class="quantity_selector">
                                    <span class="minus restarCant" onclick="cambiarCantidad(${index}, -1)">
                                        <i class="bi bi-dash"></i>
                                    </span>
                                    <span id="display${producto.id}">
                                        ${producto.cantidad}
                                    </span>
                                    <span class="plus aumentarCant" onclick="cambiarCantidad(${index}, 1)">
                                        <i class="bi bi-plus"></i>
                                    </span>
                                </div>
                            </td>
                            <td class="text-right"><strong>$</strong>${producto.precio.toFixed(2)}</td>
                            <td class="text-right">
                                <a href="#" class="btn btn-sm btn-danger" onclick="eliminarDelCarrito(${index})">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });

                // Mostrar total a pagar
                cuerpoCarrito.innerHTML += `
                    <tr style="background-color: #fff !important;">
                        <td colspan="4"></td>
                        <td style="color:#fff; background-color: #ff4545 !important;">
                            Total a Pagar:
                            <span id="totalPuntos">$ ${total.toFixed(2)}</span>
                        </td>
                    </tr>
                `;
            }
        }

        // Función para cambiar la cantidad de un producto
        function cambiarCantidad(index, cambio) {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito[index].cantidad += cambio;

            if (carrito[index].cantidad <= 0) {
                eliminarDelCarrito(index);
            } else {
                localStorage.setItem('carrito', JSON.stringify(carrito));
                cargarCarrito();
            }
        }

        // Función para eliminar un producto del carrito
        function eliminarDelCarrito(index) {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito.splice(index, 1);
            localStorage.setItem('carrito', JSON.stringify(carrito));
            cargarCarrito();
        }

        // Función para solicitar el pedido (puedes implementar la lógica que necesites)
        function solicitarPedido() {
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            if (carrito.length === 0) {
                alert('Tu carrito está vacío. Agrega productos antes de solicitar.');
                return;
            }
            // Aquí puedes agregar la lógica para procesar el pedido
            alert('Pedido solicitado con éxito.');
        }

        // Cargar el carrito al cargar la página
        document.addEventListener('DOMContentLoaded', cargarCarrito);
    </script>
</body>
</html>