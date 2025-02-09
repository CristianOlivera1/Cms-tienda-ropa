<?php
include "../../coneccionbd.php";
include "../../registrar_actividad.php";
session_start();
$usuarioId = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $talla_id = $_POST['talla_id'];
   
    // Verificar si el stock existe
    $query = "SELECT * FROM talla WHERE talId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i',  $talla_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'error' => 'La talla no existe.']);
    } else {
        // Verificar si el stock está relacionado con una oferta
        $query = "SELECT * FROM stock WHERE talId = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i',  $talla_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar la talla porque está relacionado con una stock.']);
        } else {
            $query = "DELETE FROM talla WHERE talId = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i',  $talla_id);
            if ($stmt->execute()) {
                registrarActividad($con, $usuarioId, "Talla", "Delete", "Eliminó la talla con ID: " . htmlspecialchars($talla_id) . ".");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar el talla.']);
            }
        }
    }
    exit;
}
?>