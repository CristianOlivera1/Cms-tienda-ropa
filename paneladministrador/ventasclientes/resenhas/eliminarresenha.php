<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $resenha_id = $_POST['resenha_id'];
    $query = "DELETE FROM resenhas WHERE resId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $resenha_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la reseña.']);
    }
    exit;
}
?>