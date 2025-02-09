<?php
include "../../coneccionbd.php";
include "../../registrar_actividad.php";
session_start();

$usuarioId = $_SESSION['admin_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $stock_id = $_POST['stock_id'];

    // Verificar si el stock existe
    $query = "SELECT * FROM Stock WHERE stoId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $stock_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'error' => 'El stock no existe.']);
    } else {
        // Verificar si el stock está relacionado con una oferta
        $query = "SELECT * FROM Oferta WHERE stoId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $stock_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar el stock porque está relacionado con una oferta.']);
        } else {
            $query = "DELETE FROM Stock WHERE stoId = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $stock_id);
            if ($stmt->execute()) {
                registrarActividad($con, $usuarioId, "Stock", "Delete", "Eliminó el stock con ID: " . htmlspecialchars($stock_id) . ".");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar el stock.']);
            }
        }
    }
    exit;
}
?>