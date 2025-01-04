<?php
include "coneccionbd.php";

//verifica si hay una session iniciada o crea una nueva
session_start();
// Verificar si la sesión de usuario no está establecida, entonces redirigir a la página de inicio de sesión
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); 
} else {
    $username = $_SESSION['admin_username'];
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
</head>

<body>
    <!-- Comienzo de la página -->
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
                                        <span class="d-xl-inline-block ms-1 fw-medium user-name-text"><?php print $username;?></span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- elemento -->
                                <h6 class="dropdown-header">¡Bienvenido <?php print $username;?>!</h6>
                                <a class="dropdown-item" href="/paneladministrador/logout.php"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Cerrar sesión</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

