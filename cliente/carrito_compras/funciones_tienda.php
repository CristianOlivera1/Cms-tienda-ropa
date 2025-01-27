<?php
session_start();
include('../coneccionbd.php');

/**
 * Función para obtener todos los productos de la tienda
 */
function getProductData($con)
{
    $sqlProducts = "
        SELECT 
            proId AS prodId,
            proNombre AS nombre,
            proPrecio AS precio,
            proImg AS foto
        FROM 
            producto;
    ";
    $queryProducts = mysqli_query($con, $sqlProducts);

    if (!$queryProducts) {
        return false;
    }
    return $queryProducts;
}

// Obtener productos
$productos = getProductData($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Asegúrate de incluir tus estilos -->
</head>
<body>

<div class="container">
    <h1>Productos</h1>
    <div class="productos">
        <?php if ($productos): ?>
            <?php while ($producto = mysqli_fetch_assoc($productos)): ?>
                <div class="producto">
                    <img src="<?php echo $producto['foto']; ?>" alt="<?php echo $producto['nombre']; ?>">
                    <h2><?php echo $producto['nombre']; ?></h2>
                    <p>Precio: $<?php echo $producto['precio']; ?></p>
                    <button onclick="agregarAlCarrito({id: <?php echo $producto['prodId']; ?>, nombre: '<?php echo $producto['nombre']; ?>', precio: <?php echo $producto['precio']; ?>, img: '<?php echo $producto['foto']; ?>'})">Agregar al carrito</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </div>

    <div class="carrito">
        <h2>Carrito</h2>
        <div id="checkout_items" class="checkout_items">0</div>
        <button onclick="verCarrito()">Ver Carrito</button>
    </div>
</div>

<script>
function agregarAlCarrito(producto) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito.push(producto);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarIconoCarrito();
}

function eliminarDelCarrito(productoId) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    carrito = carrito.filter(producto => producto.id !== productoId);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarIconoCarrito();
}

function actualizarIconoCarrito() {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    document.getElementById('checkout_items').innerText = carrito.length;
}

function verCarrito() {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let mensaje = 'Productos en el carrito:\n';
    carrito.forEach(producto => {
        mensaje += `${producto.nombre} - $${producto.precio}\n`;
    });
    alert(mensaje || 'El carrito está vacío.');
}

// Llama a esta función cuando se cargue la página para actualizar el icono
document.addEventListener('DOMContentLoaded', actualizarIconoCarrito);
</script>

</body>
</html>