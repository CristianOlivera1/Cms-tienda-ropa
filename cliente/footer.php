<?php include "coneccionbd.php"; ?>

<footer class="section footer-area">
        <div class="footer-top ptb_100">
                <div class="container">
                        <div class="row">
                                <div class="col-12 col-sm-6 col-lg-4">
                                        <div class="footer-items">
                                                <h3 class="footer-title text-uppercase mb-2">Sobre Nosotros</h3>
                                                <p class="mb-2">Somos una tienda de ropa dedicada a ofrecerte las últimas tendencias en moda. Nuestro objetivo es brindarte prendas de alta calidad que te hagan sentir cómodo y a la moda.</p>
                                        </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4">
                                        <div class="footer-items">
                                                <h3 class="footer-title text-uppercase mb-2">Categorías</h3>
                                                <ul>
                                                        <?php
                                                        $q = "SELECT c.catId, c.catNombre FROM categoria c JOIN producto p ON c.catId = p.catId inner join stock s on p.proId=s.proId WHERE s.stoCantidad > 0 GROUP BY c.catId ORDER BY c.catId DESC LIMIT 5";
                                                        $r123 = mysqli_query($con, $q);

                                                        while ($ro = mysqli_fetch_array($r123)) {

                                                                        $catId = "$ro[catId]";
                                                                        $catNombre = "$ro[catNombre]";
                                                                        print "
                                                                        <li class='py-2'><a class='text-black-50' href='/cliente/producto/producto.php?id=$catId'>$catNombre</a></li>
                                                                        ";
                                                        }
                                                        ?>
                                                </ul>
                                        </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                        <div class="footer-items">
                                                <h3 class="footer-title text-uppercase mb-2">Síguenos</h3>
                                                <p class="mb-2"> Síguenos en nuestras redes sociales para estar al tanto de nuestras novedades y promociones.</p>
                                                <!-- Iconos Sociales -->
                                                <ul class="social-icons list-inline pt-2">
                                                        <li class="list-inline-item px-1"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                                        <li class="list-inline-item px-1"><a href="#"><i class="fab fa-instagram"></i></a></li>
                                                </ul>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
        <div class="footer-bottom bg-grey">
                <div class="container">
                        <div class="row">
                                <div class="col-12">
                                        <div class="copyright-area d-flex flex-wrap justify-content-center justify-content-sm-between text-center py-4">
                                                <div class="unamba"> <img src="/cliente/recursos/img/footer/unamba-color.png" alt="Unamba"> Unamba</div>
                                                <div class="sistemas"> <img src="/cliente/recursos/img/footer/log-sistemas-color.png" alt="sistemas"> Ing. Informática y sistemas</div>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</footer>

<div id="menu" class="modal fade p-0">
        <div class="modal-dialog dialog-animated">
                <div class="modal-content h-100">
                        <div class="modal-header" data-dismiss="modal">
                                Menú <i class="far fa-times-circle icon-close"></i>
                        </div>
                        <div class="menu modal-body">
                                <div class="row w-100">
                                        <div class="items p-0 col-12 text-center"></div>
                                </div>
                        </div>
                </div>
        </div>
</div>

</div>

<!-- jQuery(necesario para todos los plugins de JavaScript) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap js -->
<script src="/cliente/recursos/js/bootstrap/bootstrap.min.js"></script>

<!-- Plugins js para la aniamcion scroll del header -->
<script src="/cliente/recursos/js/plugins/plugins.min.js"></script>

<!-- Activo js -->
<script src="/cliente/recursos/js/active.js"></script>
<script src="/cliente/recursos/js/script.js"></script>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>