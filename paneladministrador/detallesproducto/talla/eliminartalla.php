<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $talla_id = $_POST['talla_id'];
    $query = "DELETE FROM talla WHERE talId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $talla_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la talla.']);
    }
    exit;
}
?>