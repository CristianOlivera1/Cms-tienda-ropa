<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $cliente_id = $_POST['cliente_id'];

    // Verificar si la marca está en uso en la tabla Producto
    $query = "SELECT COUNT(*) FROM ventas WHERE cliId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'error' => 'No se puede eliminar la marca porque está en uso en el registro de ventas.']);
    } else {
        // Proceder a eliminar la marca
        $deleteQuery = "DELETE FROM cliente WHERE cliId = ?";
        $deleteStmt = $con->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $cliente_id);
        if ($deleteStmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar la marca.']);
        }
        $deleteStmt->close();
    }
    exit;
}
?>