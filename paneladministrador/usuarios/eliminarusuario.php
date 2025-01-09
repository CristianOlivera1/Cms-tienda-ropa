<?php
include "../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $usuario_id = $_POST['usuario_id'];
    $query = "DELETE FROM usuario WHERE admId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $usuario_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la usuario.']);
    }
    exit;
}
?>