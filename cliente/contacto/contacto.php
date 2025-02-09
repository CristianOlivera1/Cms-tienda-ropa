<?php include "../header.php";?>
    <!-- ***** Área de Miga de Pan Inicio ***** -->
    <section class="section breadcrumb-area overlay-dark d-flex align-items-center">
        <div class="container">
        <div class="row">
            <div class="col-12">
            <!-- Contenido de Miga de Pan -->
            <div class="breadcrumb-content text-center">
            <h2 class="text-uppercase mb-3 mt-3 text-opacity20">Contáctanos</h2>
            </div>
            </div>
        </div>
        </div>
    </section>
    <!-- ***** Área de Miga de Pan Fin ***** -->

    <!--====== Área de Contacto Inicio ======-->
    <section id="contact" class="contact-area ptb_100">
        <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-lg-5">
            <!-- Encabezado de Sección -->
            <div class="section-heading text-center mb-3">
                <h2>Contáctanos</h2>
                <p class="d-none d-sm-block mt-4">En nuestra tienda encontrarás una amplia variedad de ropa de alta calidad. Desde prendas casuales hasta atuendos formales, tenemos algo para cada ocasión. Nuestro compromiso es ofrecerte lo mejor en moda y estilo.</p>
            </div>
            <!-- Contáctanos -->
            <div class="contact-us">
                <ul>
                <!-- Información de Contacto -->
                <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-3">
                    <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                    <a class="d-block my-2" href="tel:<?php print $conTelefono ?>">
                    <h3>+51 <?php print $conTelefono ?></h3>
                    </a>
                </li>
                <!-- Información de Contacto -->
                <li class="contact-info color-3 bg-hover active hover-bottom text-center p-5 m-3">
                    <span><i class="fas fa-envelope-open-text fa-3x"></i></span>
                    <a class="d-none d-sm-block my-2" href="mailto:<?php print $conEmail ?>">
                    <h3><?php print $conEmail ?></h3>
                    </a>
                </li>
                </ul>
            </div>
            </div>
        </div>
        </div>
    </section>
    <!--====== Área de Contacto Fin ======-->

    <!--====== Área de Mapa Inicio ======-->
    <section class="section map-area">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15510.640811999261!2d-72.88364635872676!3d-13.617553300000006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x916d031110cca7df%3A0x76b548e9c776d989!2sUniversidad%20Nacional%20Micaela%20Bastidas%20de%20Apur%C3%ADmac!5e0!3m2!1ses-419!2spe!4v1733850660336!5m2!1ses-419!2spe" width="100" height="100" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
    <!--====== Área de Mapa Fin ======-->
    <?php include "../footer.php";?>
