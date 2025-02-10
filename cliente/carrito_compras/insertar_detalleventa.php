<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../coneccionbd.php'; // Asegúrate de incluir tu archivo de conexión

// Obtener los datos enviados en la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Depuración: Verifica qué datos estás recibiendo
error_log(print_r($data, true)); // Esto registrará el contenido de $data en los logs

// Verificar que el carrito no esté vacío
if (empty($data['carrito'])) {
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
    exit;
}

// Iterar sobre cada producto en el carrito y realizar la inserción
foreach ($data['carrito'] as $producto) {
    $ventaId = $data['ventaId'];
    $stoId = $producto['stoId']; // Asegúrate de que esto esté definido en el producto
    $cantidad = $producto['quantity']; // Asegúrate de que esto esté definido en el producto
    $precio = $producto['price']; // Asegúrate de que esto esté definido en el producto

    // Verificar que los datos sean válidos
    if (is_null($ventaId) || is_null($stoId) || is_null($cantidad) || is_null($precio)) {
        echo json_encode(['success' => false, 'message' => 'Datos no válidos.']);
        exit;
    }

    // Preparar la consulta para insertar en detalleventa
    $stmt = $con->prepare("INSERT INTO detalleventa (venId, stoId, detVenCantidad, detVenPrecio, detVenFechaRegis) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("siid", $ventaId, $stoId, $cantidad, $precio);

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al insertar en la base de datos: ' . $stmt->error]);
        exit;
    }

    // Cerrar la declaración
    $stmt->close();
}

// Responder con éxito
echo json_encode(['success' => true]);
?>