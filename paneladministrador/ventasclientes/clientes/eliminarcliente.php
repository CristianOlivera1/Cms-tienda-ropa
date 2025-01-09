<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $cliente_id = $_POST['cliente_id'];
    $query = "DELETE FROM cliente WHERE cliId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $cliente_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la cliente.']);
    }
    exit;
}
?>