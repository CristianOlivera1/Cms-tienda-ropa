<?php
session_start();
include_once("coneccionbd.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Recuperar nombre de usuario y contraseña de la base de datos según la entrada del usuario, evitando la inyección SQL
        $query = "SELECT u.*, c.carNombre FROM usuario u INNER JOIN cargo c ON u.carId = c.carId WHERE admUser = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['admPass'])) {
                $_SESSION['admin_id'] = $admin['admId'];
                $_SESSION['admin_username'] = $admin['admUser'];
                $_SESSION['cargo_gerente'] = $admin['carNombre']; // Guardar el nombre del cargo
                header('Location: index.php');
                exit();
            } else {
                $error = 'El nombre de usuario y/o la contraseña no coinciden.';
            }
        } else {
            $error = 'El nombre de usuario y/o la contraseña no coinciden.';
        }
    }
}
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <meta charset="utf-8" />
    <title>Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Administración y Panel de Control Multipropósito" name="description" />
    <link rel="shortcut icon" href="recursos/images/favicon/favicon.ico">
    <link href="recursos/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="recursos/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="recursos/css/app.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="#" class="d-block">
                                                    <h3 class="text-white fw-bold">MEN'S STYLE</h3>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary">¡Bienvenido de nuevo!</h5>
                                            <p class="text-muted">Inicia sesión para continuar en tu panel de control.</p>
                                        </div>
                                        <div class="mt-4">
                                            <?php if ($error): ?>
                                                <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                                                    <?php echo $error; ?>
                                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Cerrar'></button>
                                                </div>
                                            <?php endif; ?>
                                            <form class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>" method="post">
                                                <div class="mb-4">
                                                    <label for="username" class="form-label">Nombre de usuario</label>
                                                    <input type="text" class="form-control" id="username" name="username" placeholder="Introduce tu nombre de usuario">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="password-input">Contraseña</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" class="form-control pe-5" name="password" placeholder="Introduce tu contraseña" id="password-input">
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <button class="btn w-100 btn-primary" type="submit" name="login">Iniciar Sesión</button>
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>