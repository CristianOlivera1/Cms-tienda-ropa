<?php
include "../header.php";
include "../sidebar.php";

// Variables para mensajes de error o éxito
$error = '';
$success = '';

// Verificar si se ha enviado una solicitud para eliminar un comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idRes'])) {
    $idRes = intval($_POST['idRes']); 
    try {
        $stmt = $con->prepare("DELETE FROM resenhas WHERE resId = ?");
        $stmt->bind_param("i", $idRes);
        if ($stmt->execute()) {
            $success = "Comentario eliminado exitosamente.";
        } else {
            $error = "No se pudo eliminar el comentario.";
        }
    } catch (Exception $e) {
        $error = "Error al eliminar el comentario: " . $e->getMessage();
    }
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Título de la página -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Comentarios</h4>
                    </div>
                </div>
            </div>
            <!-- Fin del título de la página -->

            <!-- Mensajes de error o éxito -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <!-- Mostrar comentarios -->
            <div class="row mt-0.5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Comentarios de los compradores</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            try {
                                $stmt = $con->query("SELECT r.resId, c.cliNombre, r.resMensaje, r.resFechaRegis 
                                    FROM resenhas AS r 
                                    INNER JOIN ventas AS v ON v.venId = r.venId 
                                    INNER JOIN cliente AS c ON c.cliId = v.cliId;");

                                if ($stmt->num_rows > 0) {
                                    while ($row = $stmt->fetch_assoc()) {
                                        echo '<div class="mb-3 d-flex justify-content-between align-items-center">';
                                        echo '<div>';
                                        echo '<h6 class="fw-bold">' . htmlspecialchars($row['cliNombre']) . '</h6>';
                                        echo '<p class="mb-1">' . htmlspecialchars($row['resMensaje']) . '</p>';
                                        echo '<small class="text-muted">Publicado el: ' . htmlspecialchars($row['resFechaRegis']) . '</small>';
                                        echo '</div>';
                                        
                                        // Botón de eliminar con ícono de tacho de basura
                                        echo '<form method="POST" style="margin: 0;">';
                                        echo '<input type="hidden" name="idRes" value="' . htmlspecialchars($row['resId']) . '">';
                                        echo '<button type="submit" class="btn btn-sm" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este comentario?\');">';
                                        echo '<i class="bi bi-trash" style="font-size: 2.5rem;"></i>';
                                        echo '</button>';
                                        echo '</form>';

                                        echo '</div>';
                                        echo '<hr style="border: 1px solid gray;">';
                                    }
                                } else {
                                    echo '<p class="text-muted">No hay comentarios disponibles.</p>';
                                }
                            } catch (Exception $e) {
                                echo '<p class="text-danger">Error al cargar los comentarios: ' . $e->getMessage() . '</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../footer.php"; ?>
