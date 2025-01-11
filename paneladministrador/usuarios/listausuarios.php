<?php include "../header.php"; ?>
<?php include "../sidebar.php"; ?>
<?php
if($_SESSION['cargo_gerente']=='Gerente'){
    $gerente=$_SESSION['cargo_gerente'];
}
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Lista de Usuarios</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Todos los Usuarios</h5>
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre de Usuario</th>
                                        <th>Cargo</th>
                                        <?php if (isset($gerente)): ?>
                                            <th class="accion-col">Acción</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT admId,admUser,carNombre as cargo_nombre FROM usuario a inner join cargo c on a.carId=c.carId  ORDER BY admId DESC";
                                    $result = mysqli_query($con, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr id='usuario-{$row['admId']}'>
                                                <td>{$row['admUser']}</td>
                                                  <td>{$row['cargo_nombre']}</td>";
                                        if (isset($gerente)) {
                                            echo "<td>
                                                  <a href='editarusuario.php?id={$row['admId']}' class='btn btn-soft-secondary btn-sm ms-2 me-1 aria-label='Editar' title='Editar'><i class='ri-pencil-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                    <a href='javascript:void(0);' class='btn btn-soft-danger btn-sm' onclick='confirmDeleteUsuario({$row['admId']})' aria-label='Eliminar' title='Eliminar'><i class='ri-delete-bin-fill align-bottom me-1' style='font-size: 1.5em;'></i></a>
                                                </td>";
                                        }
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
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
                    ¿Estás seguro de que deseas eliminar este usuario?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnUsuario">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../recursos/js/script.js"></script>
    <?php include "../footer.php"; ?>
</div>