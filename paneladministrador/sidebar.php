<?php
include "coneccionbd.php";
$username=$_SESSION['admin_username'];
?>
<div class="app-menu navbar-menu">
  <!-- LOGO -->
  <div class="navbar-brand-box">

    <!-- Logo proncipal pantallas grandes-->
    <a href="index.php" class="logo logo-light">
      <span class="logo-lg">
        <img src="recursos/images/mens-store.png" alt="logo" height="45">
      </span>
    </a>
    <!-- Logo proncipal pantallas pequeñas-->
    <a href="index.php" class="logo logo-light">
      <span class="logo-sm">
        <img src="recursos/images/mens-store-small.png" alt="logo" height="40">
      </span>
    </a>

  </div>
<div id="scrollbar">
    <div class="container-fluid">

      <div id="two-column-menu">
      </div>
      <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menú</span></li>

        <li class="nav-item">
                <a href="dashboard" class="nav-link" data-key="t-analytics">  <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards"> Tablero </span></a>
              </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarK" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-tools-fill"></i> <span data-key="t-landing">Administrador</span>
                            </a>
                            <div class="menu-dropdown collapse" id="sidebarK">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="agregar-administrador.php" class="nav-link" data-key="t-nft-landing">Añadir Nuevo</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="listaadministradores.php" class="nav-link" data-key="t-nft-landing">Listar todos</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarK" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-tools-fill"></i> <span data-key="t-landing"> Configuración del Sitio </span>
                            </a>
                            <div class="menu-dropdown collapse" id="sidebarK">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="contacto.php" class="nav-link" data-key="t-nft-landing"> Actualizar Contacto </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="portada.php" class="nav-link" data-key="t-nft-landing">Titulo y descripcion portada</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
      </ul>
    </div>
  </div>

  <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
