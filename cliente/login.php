<?php
session_start();
include "coneccionbd.php";

// Habilitar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['success' => false, 'message' => 'Error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar el usuario
    $query = "SELECT * FROM cliente WHERE cliCorreo = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['cliDni'] === $password) {
            $_SESSION['user_id'] = $user['cliId'];
            $response['success'] = true;
            $response['user_id'] = $user['cliId'];
        } else {
            $response['message'] = "Contraseña incorrecta.";
        }
    } else {
        $response['message'] = "No se encontró un usuario con ese correo.";
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
?>