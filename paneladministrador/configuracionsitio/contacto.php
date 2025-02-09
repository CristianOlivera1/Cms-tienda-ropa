<?php
include "../header.php";
include "../sidebar.php";

$error = '';
$success = '';
$usuarioId = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_phone = trim($_POST['contact_phone']);
    $contact_email = trim($_POST['contact_email']);

    if (empty($contact_phone) || empty($contact_email)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!preg_match('/^9\d{8}$/', $contact_phone)) {
        $error = 'El número de teléfono debe tener exactamente 9 dígitos.';
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } else {
        $query = "UPDATE contacto SET conTelefono = ?, conEmail = ? WHERE conId = 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ss', $contact_phone, $contact_email);
        if ($stmt->execute()) {
            $success = 'Datos de contacto actualizados correctamente.';
            registrarActividad($con, $usuarioId,"Contacto", "Update", "Actualizó los datos de contacto: Teléfono - " . htmlspecialchars($contact_phone) . ", Email - " . htmlspecialchars($contact_email) . ".");
        } else {
            $error = 'Error al actualizar los datos de contacto.';
        }
        $stmt->close();
    }
} else {
    $query = "SELECT conTelefono, conEmail FROM contacto WHERE conId = 1";
    $result = $con->query($query);
    $contact = $result->fetch_assoc();
    $contact_phone = $contact['conTelefono'];
    $contact_email = $contact['conEmail'];
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Título de la página -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Contacto del Sitio</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Actualizar</a></li>
                                <li class="breadcrumb-item active">Contacto</li>
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
                                        <i class="fas fa-home"></i> Actualizar Contacto
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="contactDetails" role="tabpanel">
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
                                                    <label for="contact_phone" class="form-label">Teléfono de Contacto</label>
                                                    <input type="number" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($contact_phone); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="contact_email" class="form-label">Correo Electrónico</label>
                                                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>" required>
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
</div>

<?php include "../footer.php"; ?>