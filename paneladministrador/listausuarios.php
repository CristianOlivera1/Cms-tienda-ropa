<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>
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
                                        echo "<tr>
                                                <td>{$row['admUser']}</td>
                                                  <td>{$row['cargo_nombre']}</td>";
                                        if (isset($gerente)) {
                                            echo "<td>
                                                    <div class='dropdown d-inline-block'>
                                                        <button class='btn btn-soft-secondary btn-sm dropdown' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                            <i class='ri-more-fill align-middle'></i>
                                                        </button>
                                                        <ul class='dropdown-menu dropdown-menu-end'>
                                                            <li><a href='editarusuario.php?id={$row['admId']}' class='dropdown-item edit-item-btn'><i class='ri-pencil-fill align-bottom me-2 text-muted'></i> Editar</a></li>
                                                            <li>
                                                                <a href='javascript:void(0);' class='dropdown-item remove-item-btn' onclick='confirmDelete({$row['admId']})'>
                                                                    <i class='ri-delete-bin-fill align-bottom me-2 text-muted'></i> Eliminar
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
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
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtnAdmin">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="recursos/js/script.js"></script>
    <?php include "footer.php"; ?>
</div>