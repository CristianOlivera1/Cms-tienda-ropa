<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Asegurarse de que oferta_id esté configurado y sea un entero válido
    if (isset($_POST['oferta_id']) && filter_var($_POST['oferta_id'], FILTER_VALIDATE_INT)) {
        $ofertaId = $_POST['oferta_id'];

        // Preparar la consulta SQL para prevenir inyecciones SQL
        $query = "DELETE FROM oferta WHERE ofeId = ?";
        $stmt = $con->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param('i', $ofertaId);
            
            // Ejecutar la consulta y verificar el éxito
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar la oferta.']);
            }
            $stmt->close(); // Cerrar la declaración
        } else {
            echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID de oferta no válido.']);
    }
    
    exit();
}
?>