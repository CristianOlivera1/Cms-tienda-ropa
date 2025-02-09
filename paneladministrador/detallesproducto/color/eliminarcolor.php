<?php
include "../../coneccionbd.php";
include "../../registrar_actividad.php";

session_start();
$usuarioId = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $color_id = $_POST['color_id'];

    // Verificar si el color está en uso en la tabla Stock
    $query = "SELECT * FROM Stock WHERE colId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $color_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'No se puede eliminar el color porque está en uso en el registro de stock.']);
    } else {
        $query = "DELETE FROM color WHERE colId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $color_id);
        if ($stmt->execute()) {
            registrarActividad($con, $usuarioId, "Color", "Delete", "Eliminó el color con ID: " . htmlspecialchars($color_id) . ".");
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el color.']);
        }
    }
    exit;
}
?>