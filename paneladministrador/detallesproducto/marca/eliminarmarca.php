<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $marca_id = $_POST['marca_id'];

    // Verificar si la marca está en uso en la tabla Producto
    $query = "SELECT COUNT(*) FROM producto WHERE marId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $marca_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'error' => 'No se puede eliminar la marca porque está en uso en el registro de productos.']);
    } else {
        // Proceder a eliminar la marca
        $deleteQuery = "DELETE FROM marca WHERE marId = ?";
        $deleteStmt = $con->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $marca_id);
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