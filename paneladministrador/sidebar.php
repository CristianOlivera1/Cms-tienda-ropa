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
                            <a class="nav-link menu-link" href="#sidebarDP" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                            <i class="ri-list-check-2"></i>
                            <span data-key="t-landing">Detalles del producto</span>
                            </a>
                            <div class="menu-dropdown collapse" id="sidebarDP">
                                <ul class="nav nav-sm flex-column">
                                  
                                   <li class="nav-item">
                                        <a href="/paneladministrador/detallesproducto/categoria/gestionar-categoria.php" class="nav-link" data-key="t-nft-landing">Gestionar Categoria</a>
                                    </li>
                                    <li class="nav-item">
                                      <a href="/paneladministrador/detallesproducto/marca/gestionar-marca.php" class="nav-link" data-key="t-nft-landing">Gestionar Marca</a>
                                    </li>

                                    <li class="nav-item">
                                      <hr class="dropdown-divider mt-0 mb-0 me-4">
                                    </li>

                                    <!-- <li class="nav-item">
                                      <a href="/paneladministrador/detallesproducto/oferta/gestionar-oferta.php" class="nav-link" data-key="t-nft-landing">Gestionar oferta</a>
                                    </li> -->

                                    <li class="nav-item">
                                        <a href="/paneladministrador/detallesproducto/talla/gestionar-talla.php" class="nav-link" data-key="t-nft-landing">Gestionar talla</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/paneladministrador/detallesproducto/color/gestionar-color.php" class="nav-link" data-key="t-nft-landing">Gestionar color</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link menu-link" href="#sidebarAdmin" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                            <i><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 16 16" stroke-width="3"><path fill="currentColor" fill-rule="evenodd" d="M11.5 14c2.49 0 4.5-1 4.5-2.5V2c0-1-2-2-4.5-2S7 1 7 2v3.5c1.17.49 2.17 1.31 2.88 2.35c.49.096 1.03.149 1.62.149c1.31 0 2.4-.261 3.18-.686a4 4 0 0 0 .323-.198v1.38c0 .235-.187.6-.802.936c-.596.325-1.51.564-2.7.564q-.357 0-.68-.027q.12.495.16 1.01q.253.014.52.014c1.31 0 2.4-.261 3.18-.686a4 4 0 0 0 .323-.198v1.38c0 .236-.149.586-.791.932c-.632.34-1.58.568-2.71.568q-.345 0-.668-.028a6.4 6.4 0 0 1-.309.974q.472.053.976.053zm2.7-7.56c.615-.336.802-.701.802-.936v-1.38q-.155.106-.323.198c-.778.425-1.87.686-3.18.686s-2.4-.261-3.18-.686a4 4 0 0 1-.323-.198v1.38c0 .235.187.6.802.935c.596.325 1.51.564 2.7.564s2.1-.239 2.7-.564zM8 2.5c0-.288.125-.565.358-.734c.127-.092.265-.184.374-.234c.273-.126 1.64-.533 2.77-.533s2.11.227 2.77.533c.124.057.261.146.382.234c.231.167.35.442.35.727v.006c0 .235-.187.6-.802.936c-.596.325-1.51.564-2.7.564s-2.1-.24-2.7-.564C8.187 3.1 8 2.734 8 2.5" clip-rule="evenodd"/><path fill="currentColor" fill-rule="evenodd" d="M9 11.5C9 13.99 6.99 16 4.5 16S0 13.99 0 11.5S2.01 7 4.5 7S9 9.01 9 11.5m-1 0C8 13.43 6.43 15 4.5 15S1 13.43 1 11.5S2.57 8 4.5 8S8 9.57 8 11.5" clip-rule="evenodd"/></svg></i>
                            <span data-key="t-landing">Ventas y Clientes</span>
                          </a>
                          <div class="menu-dropdown collapse" id="sidebarAdmin">
                            <ul class="nav nav-sm flex-column">

                              <!-- <li class="nav-item">
                                <a href="/paneladministrador/ventasclientes/ventas/gestionar-ventas.php" class="nav-link" data-key="t-nft-landing">Gestionar Ventas</a>
                              </li> -->

                              <li class="nav-item">
                                <a href="/paneladministrador/ventasclientes/clientes/gestionar-clientes.php" class="nav-link" data-key="t-nft-landing">Gestionar Clientes</a>
                              </li>

                              <li class="nav-item">
                                      <hr class="dropdown-divider mt-0 mb-0 me-4">
                                </li>

                              <li class="nav-item">
                                <a href="/paneladministrador/ventasclientes/resenhas/gestionar-resenhas.php" class="nav-link" data-key="t-nft-landing">Gestionar Reseñas</a>
                              </li>

                            </ul>
                          </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarU" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-user-line"></i> <span data-key="t-landing">Usuarios</span>
                            </a>
                            <div class="menu-dropdown collapse" id="sidebarU">
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
                            <a class="nav-link menu-link" href="#sidebarCS" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-tools-fill"></i> <span data-key="t-landing"> Configuración del Sitio </span>
                            </a>
                            <div class="menu-dropdown collapse" id="sidebarCS">
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
