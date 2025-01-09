<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $marca_id = $_POST['marca_id'];
    $query = "DELETE FROM marca WHERE marId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $marca_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la marca.']);
    }
    exit;
}
?>