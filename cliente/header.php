<?php include "coneccionbd.php"; ?>
<?php
$rt = mysqli_query($con, "SELECT * FROM contacto WHERE conId=1");
$tr = mysqli_fetch_array($rt);
$conTelefono = "$tr[conTelefono]";
$conEmail = "$tr[conEmail]";
?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="EAPIIS">
    <title>Men's Store</title>
    <link rel="icon" href="/cliente/recursos/img/favicon/favicon.ico">
    <link rel="stylesheet" href="/cliente/recursos/css/style.css">
    <link rel="stylesheet" href="/cliente/recursos/css/responsive.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- Open Graph Metadata -->
    <meta property="og:title" content="Men's Store | Ropa de Moda para Hombres">
    <meta property="og:description" content="Descubre las últimas tendencias en ropa de moda para hombres. Compra ahora y luce increíble.">
    <meta property="og:image" content="https://github.com/CristianOlivera1/Resources-dev/raw/main/logoCO/preview/cms-menstore-preview.png">
    <meta property="og:url" content="http://menstore.mypressonline.com">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_ES">
    <meta property="og:site_name" content="Men's Store">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
</head>

<body>

   <!-- <div id="preloader">
        <div id="digimax-preloader" class="digimax-preloader">
            <div class="preloader-animation">
                <div class="spinner"></div>
                <div class="loader">
                    <span data-text-preloader="S" class="animated-letters">S</span>
                    <span data-text-preloader="T" class="animated-letters">T</span>
                    <span data-text-preloader="O" class="animated-letters">O</span>
                    <span data-text-preloader="R" class="animated-letters">R</span>
                    <span data-text-preloader="E" class="animated-letters">E</span>
                </div>
                <p class="fw-5 text-center text-uppercase">Cargando</p>
            </div>
            <div class="loader-animation">
                <div class="row h-100">
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div id="scrollUp" title="Desplazarse al Inicio">
        <i class="fas fa-arrow-up"></i>
    </div>

    <div class="main overflow-hidden">
        <header id="header">
            <!-- Barra de Navegación -->
            <nav data-aos="zoom-out" data-aos-delay="800" class="navbar navbar-expand">
                <div class="container header ">
                    <a class="navbar-brand" href="/">
                        <img class="navbar-brand-regular logo-header" src="/cliente/recursos/img/logo-header/mens-store-white.png" alt="logo-marca-blanco" >
                        <img class="navbar-brand-sticky logo-header" src="/cliente/recursos/img/logo-header/mens-store-dark.png" alt="logo-marca-oscuro" >
                    </a>
                    <div class="ml-auto"></div>
                    <ul class="navbar-nav items">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Inicio</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                Categorías
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/cliente/producto/producto.php">Todas</a>
                                <div class="dropdown-divider"></div>
                                <?php
                                $categories = mysqli_query($con, "SELECT DISTINCT c.catId, c.catNombre 
                                                                  FROM categoria c 
                                                                  JOIN producto p ON c.catId = p.catId 
                                                                  JOIN stock s ON p.proId = s.proId 
                                                                  WHERE s.stoCantidad > 0");
                                while ($category = mysqli_fetch_assoc($categories)) {
                                    echo "<a class='dropdown-item' href='/cliente/producto/producto.php?category={$category['catId']}'>{$category['catNombre']}</a>";
                                }
                                ?>
                            </div>
                        </li>
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0 position-relative">
                                <input class="form-control mr-sm-2" type="search" placeholder="Buscar productos..." aria-label="Buscar" id="search-input" onkeyup="showSuggestions(this.value)" style="border-radius: 50px; padding-right: 50px;">
                                <button class="btn position-absolute buscador" type="submit">
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="#000" d="m18.031 16.617l4.283 4.282l-1.415 1.415l-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9s9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617m-2.006-.742A6.98 6.98 0 0 0 18 11c0-3.867-3.133-7-7-7s-7 3.133-7 7s3.133 7 7 7a6.98 6.98 0 0 0 4.875-1.975z" />
                                        </svg>
                                    </i>
                                </button>
                                <div id="suggestions-box" class="suggestions-box mt-1"></div>
                            </form>
                        </li>
                    </ul>

                    <!-- Botón de Acción de la Barra de Navegación -->
                    <ul class="navbar-nav action">
                        <!-- Cesta -->
                    <li class="nav-item">
                            <a class="navbar-brand-sticky" href="/cliente/carrito_compras/carrito_compras.php"> 
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.148 11.479c-.101-1.428-.125-2.985-.296-4.57C15.577 4.37 14.372 2.64 12 2.64S8.423 4.37 8.148 6.908c-.171 1.586-.195 3.142-.296 4.57" stroke="black" stroke-width="1.2" stroke-miterlimit="10" stroke-linejoin="bevel"></path>
                                    <path d="M20.701 20.438V8.816H3.3v11.622H20.7z" stroke="black" stroke-width="1.5" stroke-miterlimit="10"></path>
                                </svg>
                            </a>
                            <a class="navbar-brand-regular" href="/cliente/carrito_compras/carrito_compras.php"> 
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.148 11.479c-.101-1.428-.125-2.985-.296-4.57C15.577 4.37 14.372 2.64 12 2.64S8.423 4.37 8.148 6.908c-.171 1.586-.195 3.142-.296 4.57" stroke="white" stroke-width="1.2" stroke-miterlimit="10" stroke-linejoin="bevel"></path>
                                    <path d="M20.701 20.438V8.816H3.3v11.622H20.7z" stroke="white" stroke-width="1.5" stroke-miterlimit="10"></path>
                                </svg>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="/cliente/contacto/contacto.php" class="btn ml-lg-auto btn-bordered-white contacto">
                                <!-- Icono de WhatsApp -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="30" viewBox="0 0 256 258">
                                    <defs>
                                        <linearGradient id="logosWhatsappIcon0" x1="50%" x2="50%" y1="100%" y2="0%">
                                            <stop offset="0%" stop-color="#1faf38" />
                                            <stop offset="100%" stop-color="#60d669" />
                                        </linearGradient>
                                        <linearGradient id="logosWhatsappIcon1" x1="50%" x2="50%" y1="100%" y2="0%">
                                            <stop offset="0%" stop-color="#f9f9f9" />
                                            <stop offset="100%" stop-color="#fff" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#logosWhatsappIcon0)" d="M5.463 127.456c-.006 21.677 5.658 42.843 16.428 61.499L4.433 252.697l65.232-17.104a123 123 0 0 0 58.8 14.97h.054c67.815 0 123.018-55.183 123.047-123.01c.013-32.867-12.775-63.773-36.009-87.025c-23.23-23.25-54.125-36.061-87.043-36.076c-67.823 0-123.022 55.18-123.05 123.004" />
                                    <path fill="url(#logosWhatsappIcon1)" d="M1.07 127.416c-.007 22.457 5.86 44.38 17.014 63.704L0 257.147l67.571-17.717c18.618 10.151 39.58 15.503 60.91 15.511h.055c70.248 0 127.434-57.168 127.464-127.423c.012-34.048-13.236-66.065-37.3-90.15C194.633 13.286 162.633.014 128.536 0C58.276 0 1.099 57.16 1.071 127.416m40.24 60.376l-2.523-4.005c-10.606-16.864-16.204-36.352-16.196-56.363C22.614 69.029 70.138 21.52 128.576 21.52c28.3.012 54.896 11.044 74.9 31.06c20.003 20.018 31.01 46.628 31.003 74.93c-.026 58.395-47.551 105.91-105.943 105.91h-.042c-19.013-.01-37.66-5.116-53.922-14.765l-3.87-2.295l-40.098 10.513z" />
                                    <path fill="#fff" d="M96.678 74.148c-2.386-5.303-4.897-5.41-7.166-5.503c-1.858-.08-3.982-.074-6.104-.074c-2.124 0-5.575.799-8.492 3.984c-2.92 3.188-11.148 10.892-11.148 26.561s11.413 30.813 13.004 32.94c1.593 2.123 22.033 35.307 54.405 48.073c26.904 10.609 32.379 8.499 38.218 7.967c5.84-.53 18.844-7.702 21.497-15.139c2.655-7.436 2.655-13.81 1.859-15.142c-.796-1.327-2.92-2.124-6.105-3.716s-18.844-9.298-21.763-10.361c-2.92-1.062-5.043-1.592-7.167 1.597c-2.124 3.184-8.223 10.356-10.082 12.48c-1.857 2.129-3.716 2.394-6.9.801c-3.187-1.598-13.444-4.957-25.613-15.806c-9.468-8.442-15.86-18.867-17.718-22.056c-1.858-3.184-.199-4.91 1.398-6.497c1.431-1.427 3.186-3.719 4.78-5.578c1.588-1.86 2.118-3.187 3.18-5.311c1.063-2.126.531-3.986-.264-5.579c-.798-1.593-6.987-17.343-9.819-23.64" />
                                </svg> Contáctenos
                            </a>
                        </li>
                   
                                <li class="nav-item ml-2">
                            <a class="navbar-brand-sticky" href="#" data-toggle="modal" data-target="#loginModal">
                                <button class="btn ml-lg-auto btn-bordered-white login" >Iniciar sesión</button>
                            </a>
                            <a class="navbar-brand-regular" href="#" data-toggle="modal" data-target="#loginModal">
                            <button class="btn ml-lg-auto btn-bordered-white login" >Iniciar sesión</button>

                            </a>
                        </li>
                    </ul>
                         <!-- Hamburguesa de la Barra de Navegación -->
                         <ul class="navbar-nav toggle">
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-toggle="modal" data-target="#menu">
                                <i class="fas fa-bars toggle-icon m-0"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
    </div>

    <!-- Modal de Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Ingresa tu correo" required>
                        </div>
                        <div class="form-group">
                            <label for="password">DNI (Contraseña)</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Ingresa tu DNI" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        <div id="error-message" class="alert alert-danger d-none mt-3" role="alert"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar la recarga de la página

    var formData = new FormData(this);

    fetch('../../cliente/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirigir a la página de seguimiento con el ID del usuario
            window.location.href = '../../cliente/seguimiento.php?id=' + data.user_id;
        } else {
            // Mostrar el mensaje de error
            var errorMessage = document.getElementById('error-message');
            errorMessage.textContent = data.message;
            errorMessage.classList.remove('d-none');
        }
    })
    .catch(error => console.error('Error:', error));
});
    </script>

    <style>
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: rgba(255, 0, 0, 0.1); /* Fondo rojo claro */
            border: 1px solid rgba(255, 0, 0, 0.5); /* Borde rojo */
            color: #d9534f; /* Color del texto */
        }
    </style>

    <!-- Scripts de Bootstrap (asegúrate de incluir jQuery y Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>