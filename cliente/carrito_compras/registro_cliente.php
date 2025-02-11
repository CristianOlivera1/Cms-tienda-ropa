<?php
// Incluir la conexión a la base de datos al principio del archivo
include '../coneccionbd.php';

// Inicializar variables para mensajes
$mensaje_exito = '';
$mensaje_error = '';
$cliId = null;
$cerrar_modal = false;

// Procesar el registro de cliente y la compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Buscar cliente por DNI
    if (isset($_POST['buscar'])) {
        $dni_buscar = $_POST['buscarDni'];
        $stmt = $con->prepare("SELECT cliId, cliNombre, cliApellidoPaterno, cliApellidoMaterno FROM cliente WHERE cliDni = ?");
        $stmt->bind_param("s", $dni_buscar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cliente = $result->fetch_assoc();
            $mensaje_exito = 'Cliente encontrado: ' . $cliente['cliNombre'] . ' ' . $cliente['cliApellidoPaterno'] . ' ' . $cliente['cliApellidoMaterno'] . '.';
            $cliId = $cliente['cliId'];
        } else {
            $mensaje_error = 'No se encontró el cliente. Debe registrarse.';
        }
    }

    // Procesar compra
    if (isset($_POST['comprar'])) {
        $cliId = $_POST['cliId'];

        // Insertar en la tabla ventas
        $stmt = $con->prepare("INSERT INTO ventas (cliId, venFechaRegis, estVenId) VALUES (?, NOW(),?)");
        
        $estado=2;
        $stmt->bind_param("ii", $cliId,$estado);
        
        if ($stmt->execute()) {
            $venId = $stmt->insert_id; // Obtener el ID de la venta generada
            // Redirigir a la página de confirmación de compra con el venId
            header("Location: confirmar_compra.php?cliId=" . $cliId . "&venId=" . $venId);
            exit;
        } else {
            $mensaje_error = 'Error al registrar la venta: ' . $stmt->error;
        }
    }

    // Procesar registro de cliente
    if (isset($_POST['registrar'])) {
        $nombre = $_POST['cliNombre'];
        $apePaterno = $_POST['cliApellidoPaterno'];
        $apeMaterno = $_POST['cliApellidoMaterno'];
        $dni = $_POST['cliDni'];
        $correo = $_POST['cliCorreo'];
        $fechaNacimiento = $_POST['cliFechNacimiento'];

        // Validación de edad
        $fecha_actual = new DateTime();
        $fecha_nac = new DateTime($fechaNacimiento);
        $edad = $fecha_nac->diff($fecha_actual)->y;

        if ($edad < 18) {
            $mensaje_error = 'Debe ser mayor de 18 años para registrarse.';
        } else {
            // Verificar si el cliente ya existe por DNI
            $stmt = $con->prepare("SELECT cliId FROM cliente WHERE cliDni = ?");
            $stmt->bind_param("s", $dni);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $mensaje_error = 'El DNI ya está registrado, ingrese en el buscador para continuar.';
            } else {
                // Verificar si el correo ya está registrado
                $stmt = $con->prepare("SELECT cliId FROM cliente WHERE cliCorreo = ?");
                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $mensaje_error = 'El correo electrónico ya está registrado.';
                } else {
                    // Iniciar transacción
                    $con->begin_transaction();
                    try {
                        $sql_cliente = "INSERT INTO cliente (cliNombre, cliApellidoPaterno, cliApellidoMaterno, cliDni, cliCorreo, cliFechNacimiento) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmt = $con->prepare($sql_cliente);
                        $stmt->bind_param("ssssss", $nombre, $apePaterno, $apeMaterno, $dni, $correo, $fechaNacimiento);
                        
                        if ($stmt->execute()) {
                            $cliId = $stmt->insert_id; // Obtener el ID generado
                            $con->commit();
                            $mensaje_exito = 'Cliente: ' . $nombre . ' '.$apePaterno.' '.$apeMaterno.'. con DNI '.$dni.' fue creado exitosamente';
                            $cerrar_modal = true; // Variable para cerrar el modal
                        } else {
                            throw new Exception("Error al ejecutar la inserción: " . $stmt->error);
                        }
                    } catch (Exception $e) {
                        $con->rollback();
                        $mensaje_error = 'Error al registrar: ' . $e->getMessage();
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('../recursos/img/welcome/fondo-portada.svg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card p-4">
            <h2 class="text-center"><i class="bi bi-person-plus-fill"></i> ¿Realizaste compras antes?</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="buscarDni" class="form-label">Ingrese su DNI para buscar</label>
                    <input type="text" class="form-control" id="buscarDni" name="buscarDni" pattern="^\d{8}$" title="Debe tener exactamente 8 dígitos" required>
                </div>
                <button type="submit" class="btn btn-info w-100" name="buscar"><i class="bi bi-search"></i> Buscar Cliente</button>
            </form>

            <div class="mt-3">
    <?php
    if (!empty($mensaje_exito) && $cliId !== null) {
        echo '<div class="alert alert-success">' . $mensaje_exito . '</div>';
        echo '<form method="POST" action="">
                <input type="hidden" name="cliId" value="' . $cliId . '">
                <button type="submit" class="btn btn-primary w-100" name="comprar"><i class="bi bi-cart-check"></i> Comprar con este usuario</button>
              </form>';
    } elseif (!empty($mensaje_error)) {
        echo '<div class="alert alert-danger">' . $mensaje_error . '</div>';
    }
    ?>
</div>

            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-volver w-100" onclick="window.location.href='carrito_compras.php'">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </button>
                </div>
                <div class="col-md-6">
 <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="bi bi-person-add"></i> Registrarse
                    </button>
                </div>
            </div>

            <!-- Modal para Registro de Cliente -->
            <div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="registroModalLabel"><i class="bi bi-person-plus-fill"></i> Registro de Cliente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="">
                                
                                <div class="mb-3">
                                    <label for="cliNombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="cliNombre" name="cliNombre" pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" title="Solo letras y espacios" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cliApellidoPaterno" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="cliApellidoPaterno" name="cliApellidoPaterno" pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" title="Solo letras y espacios" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cliApellidoMaterno" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="cliApellidoMaterno" name="cliApellidoMaterno" pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ ]+$" title="Solo letras y espacios" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cliDni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="cliDni" name="cliDni" pattern="^\d{8}$" title="Debe tener exactamente 8 dígitos" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cliCorreo" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="cliCorreo" name="cliCorreo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cliFechNacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="cliFechNacimiento" name="cliFechNacimiento" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" name="registrar"><i class="bi bi-person-add"></i> Registrarse</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($cerrar_modal) && $cerrar_modal): ?>
        <script>
            var registroModal = document.getElementById('registroModal');
            registroModal.addEventListener('hidden.bs.modal', function () {
                location.reload(); // Recargar la página después de cerrar el modal
            });
        </script>
    <?php endif; ?>
</body>
</html>