<?php
include '../../coneccionbd.php'; // Asegúrate de incluir tu archivo de conexión

// Obtener los datos enviados en la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos sean válidos
if (empty($data['venId'])) {
    echo json_encode(['success' => false, 'message' => 'ID de venta no proporcionado.']);
    exit;
}

$venId = $data['venId'];

// Iniciar una transacción
$con->begin_transaction();

try {
    // Obtener los detalles de la venta
    $stmt = $con->prepare("SELECT stoId, detVenCantidad FROM detalleventa WHERE venId = ?");
    $stmt->bind_param("s", $venId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Actualizar el stock para cada producto en la venta
    while ($row = $result->fetch_assoc()) {
        $stoId = $row['stoId'];
        $cantidadVendida = $row['detVenCantidad'];

        // Actualizar la cantidad de stock
        $updateStmt = $con->prepare("UPDATE stock SET stoCantidad = stoCantidad - ? WHERE stoId = ?");
        $updateStmt->bind_param("is", $cantidadVendida, $stoId);
        $updateStmt->execute();
        $updateStmt->close();
    }

    // Actualizar el estado de la venta
    $updateVentaStmt = $con->prepare("UPDATE ventas SET estVenId = 1 WHERE venId = ?");
    $updateVentaStmt->bind_param("s", $venId);
    $updateVentaStmt->execute();
    $updateVentaStmt->close();

    // Confirmar la transacción
    $con->commit();

    echo json_encode(['success' => true, 'message' => 'Venta confirmada y stock actualizado.']);
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $con->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al confirmar la venta: ' . $e->getMessage()]);
}
?>