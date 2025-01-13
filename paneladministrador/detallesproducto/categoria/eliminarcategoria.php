<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $categoria_id = $_POST['categoria_id'];

    // Verificar si la categoría está en uso en la tabla Producto
    $query = "SELECT * FROM producto WHERE catId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'No se puede eliminar la categoría porque está en uso en el registro de productos.']);
    } else {
        $query = "DELETE FROM categoria WHERE catId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $categoria_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar la categoría.']);
        }
    }
    exit;
}
?>