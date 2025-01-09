<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $color_id = $_POST['color_id'];
    $query = "DELETE FROM color WHERE colId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $color_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la color.']);
    }
    exit;
}
?>