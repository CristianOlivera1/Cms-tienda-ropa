<?php
include "../../coneccionbd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Asegurarse de que oferta_id esté configurado y sea un entero válido
    if (isset($_POST['oferta_id']) && filter_var($_POST['oferta_id'], FILTER_VALIDATE_INT)) {
        $ofertaId = $_POST['oferta_id'];

        // Obtener el stoId relacionado con la oferta
        $stockQuery = "SELECT stoId FROM oferta WHERE ofeId = ?";
        $stockStmt = $con->prepare($stockQuery);
        
        if ($stockStmt) {
            $stockStmt->bind_param('i', $ofertaId);
            $stockStmt->execute();
            $stockStmt->bind_result($stoId);
            $stockStmt->fetch();
            $stockStmt->close();

            // Verificar si se encontró un stoId
            if ($stoId) {
                // Actualizar el estado del stock relacionado
                $updateQuery = "UPDATE stock SET estId = 1 WHERE stoId = ?";
                $updateStmt = $con->prepare($updateQuery);
                
                if ($updateStmt) {
                    $updateStmt->bind_param('i', $stoId);
                    $updateStmt->execute();
                    $updateStmt->close(); // Cerrar la declaración de actualización
                }
            }

            // Preparar la consulta SQL para eliminar la oferta
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
                echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta de eliminación.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta para obtener stoId.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID de oferta no válido.']);
    }
    
    exit();
}
?>