<?php
include "../../header.php";
include "../../sidebar.php";
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reporte de Usuarios</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Usuarios</a></li>
                                <li class="breadcrumb-item active">Reporte</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reporte de Actividades Recientes -->
            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#actividadesDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-list"></i> Actividades Recientes
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <?php
                                    // Obtener los nombres de usuarios para el select
                                    $queryUsuarios = "SELECT DISTINCT u.admUser FROM usuario u INNER JOIN actividades a ON u.admId = a.usuarioId";
                                    $resultUsuarios = $con->query($queryUsuarios);

                                    $usuarios = [];
                                    while ($row = $resultUsuarios->fetch_assoc()) {
                                        $usuarios[] = $row['admUser'];
                                    }

                                    // Generar el HTML para el select de usuarios
                                    echo '<select id="filtroUsuario" class="form-select">';
                                    echo '<option value="">Seleccione un usuario</option>';
                                    foreach ($usuarios as $usuario) {
                                        echo '<option value="' . htmlspecialchars($usuario) . '">' . htmlspecialchars($usuario) . '</option>';
                                    }
                                    echo '</select>';
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <select id="filtroTabla" class="form-select">
                                        <option value="">Nombre de la tabla</option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Stock">Stock</option>
                                        <option value="Producto">Producto</option>
                                        <option value="Talla">Talla</option>
                                        <option value="Marca">Marca</option>
                                        <option value="Color">Color</option>
                                        <option value="Categoria">Categoria</option>
                                        <option value="Contacto">Contacto</option>
                                        <option value="Portada">Portada</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select id="filtroActividad" class="form-select">
                                        <option value="">Actividad</option>
                                        <option value="Insert">Insert</option>
                                        <option value="Update">Update</option>
                                        <option value="Delete">Delete</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <input type="datetime-local" id="filtroFechaDesde" class="form-control" placeholder="Fecha y hora desde">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <input type="datetime-local" id="filtroFechaHasta" class="form-control" placeholder="Fecha y hora hasta">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <button class="btn btn-primary w-100" onclick="cargarActividades(1)">Filtrar</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div id="total-registros"></div>
                            </div>
                            <nav class="d-flex justify-content-end">
                                <ul class="pagination" id="pagination">
                                    <!-- Paginación -->
                                </ul>
                            </nav>
                            <div class="tab-content">
                                <div class="tab-pane active" id="actividadesDetails" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>N</th>
                                                    <th>Usuario</th>
                                                    <th>Nombre de la tabla</th>
                                                    <th>Actividad</th>
                                                    <th>Descripción</th>
                                                    <th>Fecha</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="actividades-table">
                                                <!-- Datos de la tabla -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                ¿Estás seguro de que deseas eliminar esta actividad?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        cargarActividades(1);
    });

    function cargarActividades(pagina) {
        const usuario = document.getElementById('filtroUsuario').value;
        const tabla = document.getElementById('filtroTabla').value;
        const actividad = document.getElementById('filtroActividad').value;
        const fechaDesde = document.getElementById('filtroFechaDesde').value;
        const fechaHasta = document.getElementById('filtroFechaHasta').value;

        const params = new URLSearchParams({
            pagina: pagina,
            usuario: usuario,
            tabla: tabla,
            actividad: actividad,
            fechaDesde: fechaDesde,
            fechaHasta: fechaHasta
        });

        fetch('obtener_reporte_usuarios.php?' + params.toString())
            .then(response => response.json())
            .then(data => {
                // Actualizar tabla de Actividades Recientes
                const actividadesTable = document.getElementById('actividades-table');
                actividadesTable.innerHTML = '';
                data.actividades.forEach((actividad, index) => {
                    let badgeColor;
                    switch (actividad.actividad.toLowerCase()) {
                        case 'insert':
                            badgeColor = 'success';
                            break;
                        case 'update':
                            badgeColor = 'warning';
                            break;
                        case 'delete':
                            badgeColor = 'danger';
                            break;
                        default:
                            badgeColor = 'secondary';
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>${(pagina - 1) * 15 + index + 1}</td>
                <td>${actividad.usuario}</td>
                <td>${actividad.nombreTabla}</td>
                <td><span class="badge-${badgeColor}">${actividad.actividad}</span></td>
                <td>${actividad.descripcion}</td>
                <td>${actividad.fecha}</td>
                <td><button class='btn btn-soft-danger btn-sm' onclick="confirmarEliminacion(${actividad.actId})"><i class="ri-delete-bin-fill align-bottom me-1" style='font-size: 1.5em;'></i></button></td>
                `;
                    actividadesTable.appendChild(row);
                });

                // Actualizar total de registros
                document.getElementById('total-registros').innerText = `Total de registros: ${data.total}`;

                // Actualizar paginación
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';
                for (let i = 1; i <= data.totalPaginas; i++) {
                    const li = document.createElement('li');
                    li.classList.add('page-item');
                    if (i === pagina) {
                        li.classList.add('active');
                    }
                    li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="cargarActividades(${i})">${i}</a>`;
                    pagination.appendChild(li);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function confirmarEliminacion(actId) {
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.onclick = function() {
            eliminarActividad(actId);
        };
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    function eliminarActividad(actId) {
        fetch('eliminar_actividad.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    actId: actId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cargarActividades(1);
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                    deleteModal.hide();
                } else {
                    alert('Error al eliminar la actividad.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>
<?php include "../../footer.php"; ?>