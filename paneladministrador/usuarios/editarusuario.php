<?php include "../header.php";
include "../sidebar.php";

$error = '';
$success = '';
$usuarioId = $_SESSION['admin_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $carId = mysqli_real_escape_string($con, $_POST['carId']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $query = "SELECT * FROM usuario WHERE admUser = ? AND admId != ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $username, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El nombre de usuario ya está en uso.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE usuario SET admUser = ?, admPassword = ?, carId = ? WHERE admId = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('ssii', $username, $hashed_password, $carId, $id);
            if ($stmt->execute()) {
                registrarActividad($con, $id, "Usuario", "Update", "Actualizó el usuario: Nombre de Usuario - " . htmlspecialchars($username) . ", Cargo ID - " . htmlspecialchars($carId) . ".");
                $success = 'Usuario actualizado correctamente.';
            } else {
                $error = 'Error al actualizar el usuario.';
            }
        }
    }
} else {
    $query = "SELECT * FROM usuario WHERE admId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $username = $admin['admUser'];
    $carId = $admin['carId'];
}

?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Editar usuario</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="listausuarios.php">Lista usuario</a></li>
                                <li class="breadcrumb-item active">Editar</li>
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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" aria-selected="false">
                                        </i> Editar usuario
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible alert-outline fade show"><?php echo $error; ?> <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
                                    <?php endif; ?>
                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible alert-outline fade show"><?php echo $success; ?> <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>
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
                                                    <select class="form-select" id="cargoSelect" name="carId">
                                                        <option selected>Seleccione cargo</option>
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
                                                        <input type="password" class="form-control" id="password" name="password" required>
                                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary">Actualizar</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../recursos/js/script.js"></script>
</div>

<?php include "../footer.php"; ?>