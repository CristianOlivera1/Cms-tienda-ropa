<?php
include "../coneccionbd.php";
include "../registrar_actividad.php";
session_start();

$usuarioId = $_SESSION['admin_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $usuario_id = $_POST['usuario_id'];
    $query = "DELETE FROM usuario WHERE admId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $usuario_id);
    if ($stmt->execute()) {
        registrarActividad($con, $usuarioId, "Usuario", "Delete", "Eliminó el usuario con ID: " . htmlspecialchars($usuario_id) . ".");
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el usuario.']);
    }
    exit;
}
?>