<?php
session_start();
include "../../coneccionbd.php"; 

/**
 * Elimina un detalle de venta dado su ID.
 *
 * @param int $detVenId El ID del detalle de venta a eliminar.
 * @return array Un array con el resultado de la operación.
 */
function eliminarDetalleVenta($detVenId) {
    global $con; 

    // Prepara la consulta para eliminar el detalle de venta
    $query = "DELETE FROM detalleventa WHERE detVenId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $detVenId);

    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, devuelve un mensaje de éxito
        return ['success' => true, 'message' => 'Detalle de venta eliminado exitosamente.'];
    } else {
        // Si ocurrió un error
        return ['success' => false, 'message' => 'Error al eliminar el detalle de venta. Por favor, inténtelo de nuevo.'];
    }
}

// Verifica si se ha recibido el parámetro detVenId
if (isset($_GET['detVenId'])) {
    $detVenId = $_GET['detVenId'];
    $resultado = eliminarDetalleVenta($detVenId);
    echo json_encode($resultado);
} else {
    // Si no se proporciona detVenId
    echo json_encode(['success' => false, 'message' => 'No se ha proporcionado el ID del detalle de venta.']);
}
?>