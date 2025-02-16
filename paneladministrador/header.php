<?php
include "coneccionbd.php";
// Habilitar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica si hay una sesión iniciada o crea una nueva
session_start();
// Verificar si la sesión de usuario no está establecida, entonces redirigir a la página de inicio de sesión
if (!isset($_SESSION['admin_id'])) {
    header("Location: /paneladministrador/login.php"); 
    exit();
} else {
    $username = $_SESSION['admin_username'];
    $usuarioId = $_SESSION['admin_id'];

    // Recuperar los datos del usuario
    $sql = "SELECT admId, admUser, admPassword FROM usuario WHERE admId = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $stmt->bind_result($admId, $admUser, $admPassword);
    $stmt->fetch();
    $stmt->close();
}

// Manejo de la actualización del perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asegúrate de que no haya espacios en el nombre del campo
    $username = isset($_POST['admUser']) ? trim($_POST['admUser']) : ''; 
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);

    // Validaciones
    if (empty($username) || empty($currentPassword)) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario y la contraseña actual son obligatorios.']);
        exit();
    } elseif (strlen($newPassword) > 0 && strlen($newPassword) < 8) {
        echo json_encode(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 8 caracteres.']);
        exit();
    } else {
        // Verificar la contraseña actual
        $sql = "SELECT admPassword FROM usuario WHERE admId = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($currentPassword, $hashedPassword)) {
            // Contraseña actual correcta, proceder a actualizar
            if (!empty($newPassword)) {
                $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "UPDATE usuario SET admUser = ?, admPassword = ? WHERE admId = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ssi", $username, $newPasswordHashed, $admId);
            } else {
                $sql = "UPDATE usuario SET admUser = ? WHERE admId = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("si", $username, $admId);
            }

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil.']);
                exit();
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña actual incorrecta.']);
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    <meta charset="utf-8" />
    <title>Tablero | Mens' Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Panel de administración de Men's Style" name="description" />
    <link rel="shortcut icon" href="/paneladministrador/recursos/images/favicon/favicon.ico">
    <link href="/paneladministrador/recursos/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/paneladministrador/recursos/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/paneladministrador/recursos/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Estilos personalizados para centrar y ajustar el modal */
        .modal-content {
            margin: 0 auto;
            max-width: 300px; /* Ajusta el ancho máximo del modal */
        }
        .modal-body {
            padding: 20px;
        }
        .form-control {
            width: 100%; /* Asegura que los inputs ocupen el 100% del ancho */
        }
        .modal-footer {
            justify-content: center; /* Centra los botones en el footer */
        }
    </style>
</head>

<body>
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-xl-inline-block ms-1 fw-medium user-name-text"><?php print $username; ?></span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">¡Bienvenido <?php print $username; ?>!</h6>
                                <a class="dropdown-item" href="/paneladministrador/logout.php"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Cerrar sesión</span></a>
                                <a class="dropdown-item" href="#" id="editProfileButton"><i class="mdi mdi-account text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Mi perfil</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Modal para editar perfil -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-center w-100" id="editProfileModalLabel"><i class="fas fa-user-edit me-2"></i>Editar Perfil</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProfileForm" method="POST" action="">
                            <div class="mb-3">
                                <label for="admId" class="form-label text-center"><i class="fas fa-id-card"></i>Codigo de Usuario</label>
                                <input type="text" class="form-control " id="admId" name="admId" value="<?php echo htmlspecialchars($admId); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="admUser" class="form-label text-center" ><i class="fas fa-user me-2"></i>Nombre de Usuario</label>
                                <input type="text" class="form-control" id="admUser" name="admUser" value="<?php echo htmlspecialchars($admUser); ?>" required autocomplete="username">
                            </div>
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label text-center"><i class="fas fa-lock me-2"></i>Contraseña Actual</label>
                                <input type="password" class="form-control text-center" id="currentPassword" name="currentPassword" required autocomplete="current-password">
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label text-center"><i class="fas fa-key me-2"></i>Nueva Contraseña (opcional)</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword" autocomplete="new-password">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para mensajes de éxito o error -->
        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="messageModalLabel"><i class="fas fa-check-circle me-2"></i>Mensaje</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="messageModalBody">
                        <!-- Mensaje dinámico -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        $(document).ready(function() {
            // Abrir el modal al hacer clic en "Mi perfil"
            $('#editProfileButton').on('click', function(e) {
                e.preventDefault();
                $('#editProfileModal').modal('show');
            });

            // Enviar el formulario via AJAX
            $('#editProfileForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '', // Enviar al mismo archivo
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        try {
                            response = JSON.parse(response);
                            if (response.success) {
                                $('#messageModalBody').html('<p class="text-success"><i class="fas fa-check-circle me-2"></i>' + response.message + '</p>');
                                $('#messageModal').modal('show');
                                $('#editProfileModal').modal('hide');
                                setTimeout(function() {
                                    location.reload(); // Recargar la página para reflejar los cambios
                                }, 2000);
                            } else {
                                $('#messageModalBody').html('<p class="text-danger"><i class="fas fa-exclamation-circle me-2"></i>' + response.message + '</p>');
                                $('#messageModal').modal('show');
                            }
                        } catch (e) {
                            $('#messageModalBody').html('<p class="text-danger"><i class="fas fa-exclamation-circle me-2"></i>Error en la respuesta del servidor.</p>');
                            $('#messageModal').modal('show');
                        }
                    },
                    error: function() {
                        $('#messageModalBody').html('<p class="text-danger"><i class="fas fa-exclamation-circle me-2"></i>Error al actualizar el perfil.</p>');
                        $('#messageModal').modal('show');
                    }
                });
            });
        });
        </script>
    </div>
</body>
</html>