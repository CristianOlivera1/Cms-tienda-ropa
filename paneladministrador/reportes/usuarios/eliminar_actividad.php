<?php
include "../../coneccionbd.php";

$data = json_decode(file_get_contents('php://input'), true);
$actId = $data['actId'];

$query = "DELETE FROM actividades WHERE actId = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $actId);
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
?>