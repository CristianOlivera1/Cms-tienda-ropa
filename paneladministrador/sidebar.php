<?php
include "coneccionbd.php";
$username=$_SESSION['admin_username'];

if($_SESSION['cargo_gerente']=='Gerente'){

  $gerente=$_SESSION['cargo_gerente'];
  }
  
?>
<div class="app-menu navbar-menu">
  <div class="navbar-brand-box">

    <!-- Logo proncipal pantallas grandes-->
    <a href="/paneladministrador/index.php" class="logo logo-light">
      <span class="logo-lg">
        <img src="/paneladministrador/recursos/images/mens-store.png" alt="logo" height="45">
      </span>
    </a>
    <!-- Logo proncipal pantallas pequeñas-->
    <a href="/paneladministrador/index.php" class="logo logo-light">
      <span class="logo-sm">
        <img src="/paneladministrador/recursos/images/mens-store-small.png" alt="logo" height="40">
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
                <a href="/paneladministrador/index.php" class="nav-link" data-key="t-analytics">  <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards"> Tablero </span></a>
              </li>

                      <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarK" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                            <i class="ri-list-check-2"></i>
                            <span data-key="t-landing">Detalles del producto</span>
                            </a>
                            <div class="menu-dropdown collapse" id="sidebarK">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="/paneladministrador/detallesproducto/gestionar-oferta.php" class="nav-link" data-key="t-nft-landing">Gestionar oferta</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="/paneladministrador/detallesproducto/gestionar-talla.php" class="nav-link" data-key="t-nft-landing">Gestionar talla</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/paneladministrador/detallesproducto/gestionar-color.php" class="nav-link" data-key="t-nft-landing">Gestionar color</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarK" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-user-line"></i> <span data-key="t-landing">Usuarios</span>
                            </a>
                            <div class="menu-dropdown collapse" id="sidebarK">
                                <ul class="nav nav-sm flex-column">
                                <?php if (isset($gerente)): ?>
                                    <li class="nav-item">
                                        <a href="/paneladministrador/usuarios/agregar-usuario.php" class="nav-link" data-key="t-nft-landing">Añadir Nuevo</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="/paneladministrador/usuarios/listausuarios.php" class="nav-link" data-key="t-nft-landing">Listar todos</a>
                                    </li>
                                    <?php else: ?>
                                    <li class="nav-item">
                                        <a href="/paneladministrador/usuarios/listausuarios.php" class="nav-link" data-key="t-nft-landing">Listar todos</a>
                                    </li>
                                    <?php endif; ?>
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
                                        <a href="/paneladministrador/configuracionsitio/contacto.php" class="nav-link" data-key="t-nft-landing"> Actualizar Contacto </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/paneladministrador/configuracionsitio/portada.php" class="nav-link" data-key="t-nft-landing">Titulo y descripcion portada</a>
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
