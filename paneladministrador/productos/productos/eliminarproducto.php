<?php
include "../../coneccionbd.php";
include "../../registrar_actividad.php";

session_start();
$usuarioId = $_SESSION['admin_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['producto_id'])) {
    $producto_id = (int)$_POST['producto_id'];

    // Comprobar si el producto existe
    $query = "SELECT * FROM producto WHERE proId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'error' => 'El producto no existe.']);
        exit;
    }

    // Comprobar si el producto está relacionado con una oferta
    $query = "SELECT * FROM stock WHERE proId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'No se puede eliminar el producto porque está relacionado con una oferta.']);
        exit;
    }

    // Eliminar el producto
    $query = "DELETE FROM producto WHERE proId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $producto_id);
    if ($stmt->execute()) {
        registrarActividad($con, $usuarioId, "Producto", "Delete", "Eliminó el producto con ID: " . htmlspecialchars($producto_id) . ".");
        echo json_encode(['success' => true, 'message' => 'Producto eliminado con éxito.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto.']);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Solicitud inválida.']);
}
?>