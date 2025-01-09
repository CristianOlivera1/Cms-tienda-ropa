<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $categoria_id = $_POST['categoria_id'];
    $query = "DELETE FROM categoria WHERE catId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $categoria_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la categoria.']);
    }
    exit;
}
?>