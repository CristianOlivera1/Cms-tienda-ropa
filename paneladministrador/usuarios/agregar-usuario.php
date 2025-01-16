<?php
ob_start();
include "../header.php";
include "../sidebar.php";

$error = '';
$username = '';
$password = '';
$confirm_password = '';
$carId = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $carId = trim($_POST['carId']);

    if (empty($username) || empty($password) || empty($confirm_password) || empty($carId)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $query = "SELECT * FROM usuario WHERE admUser = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El nombre de usuario ya está en uso.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO usuario (admUser, admPassword, carId) VALUES (?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param('sss', $username, $hashed_password, $carId);
            if ($stmt->execute()) { 
                // Redirigir después de registrar el administrador
                header("Location: listausuarios.php"); 
                exit();
            } else {
                $error = 'Error al registrar el usuario.';
            }
        }
    }
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Agregar usuario</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">usuario</a></li>
                                <li class="breadcrumb-item active">Agregar</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i class="fas fa-user"></i> Agregar usuario
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible alert-outline fade show"><?php echo $error; ?><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <form method="POST" action="">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Nombre de Usuario</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cargoSelect" class="form-label">Cargo</label>
                                                    <select class="form-select" id="cargoSelect" name="carId" required>
                                                        <option value="">Seleccione cargo</option>
                                                        <?php
                                                        $result = mysqli_query($con, "SELECT carId, carNombre FROM cargo order by carId desc");
                                                        while($row = mysqli_fetch_assoc($result)) {
                                                            $selected = ($row['carId'] == $carId) ? 'selected' : '';
                                                            echo "<option value='".$row['carId']."' $selected>".$row['carNombre']."</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Contraseña</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
                                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../recursos/js/script.js"></script>
</div>

<?php
include "../footer.php";
ob_end_flush();
?>