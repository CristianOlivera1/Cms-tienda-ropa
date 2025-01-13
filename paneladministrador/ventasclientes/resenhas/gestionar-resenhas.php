<?php
include "../../header.php";
include "../../sidebar.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resenha_id = $_POST['resenha_id'];

    $query = "DELETE FROM resenhas WHERE resId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $resenha_id);
    if ($stmt->execute()) {
        $success = 'Reseña eliminada exitosamente.';
    } else {
        $error = 'Error al eliminar la reseña.';
    }
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Gestionar Reseñas</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Reseñas</a></li>
                                <li class="breadcrumb-item active">Gestionar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Lista de Reseñas</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $query = "SELECT r.resId, c.cliNombre, r.resMensaje, r.resFechaRegis 
                                      FROM resenhas AS r 
                                      INNER JOIN ventas AS v ON v.venId = r.venId 
                                      INNER JOIN cliente AS c ON c.cliId = v.cliId";
                            $result = mysqli_query($con, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='comment' id='resenha-{$row['resId']}'>
                                        <div class='d-flex'>
                                            <div class='flex-shrink-0 me-3'>
                                                <img class='rounded-circle avatar-sm' src='../../recursos/images/avatar/user.svg' alt=''>
                                            </div>
                                            <div class='flex-grow-1'>
                                                <h5 class='mt-0 mb-1'>{$row['cliNombre']}
                                                    <small class='text-muted'>{$row['resFechaRegis']}</small>
                                               <a href='javascript:void(0);' class='btn btn-soft-danger float-end me-5 btn-sm' onclick='confirmDeleteResenha({$row['resId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                </h5>
                                                <p class='text-muted'>{$row['resMensaje']}</p>
                                            </div>
                                        </div>
                                      </div>
                                      <hr>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta reseña?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnResenha">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../recursos/js/script.js"></script>

</div>

<?php include "../../footer.php"; ?>