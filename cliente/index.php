<?php include "header.php"; ?>
<?php
$rr = mysqli_query($con, "SELECT * FROM portada");
$r = mysqli_fetch_row($rr);
$porTitulo = $r[1];
$porDescripcion = $r[2];

$result = mysqli_query($con, "SELECT count(*) FROM producto");
$row = mysqli_fetch_row($result);
$numrows = $row[0];
?>
<section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-7">
                <div class="welcome-intro">
                    <h1 class="text-white"><?php echo $porTitulo ?></h1>
                    <p class="white-70 my-4"><?php echo $porDescripcion ?></p>
                    <div class="button-group">
                        <a href="producto/producto.php" class="btn btn-bordered-white">Ver productos</a>
                        <a href="#" class="btn btn-bordered-white d-none d-sm-inline-block">Contáctanos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="shape shape-bottom">
        <img src="recursos/img/welcome/fondo-portada.svg" alt="fondo-portada">
    </div>
</section>

<section id="service" class="section service-area bg-grey ptb_150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="section-heading text-center">
                    <h1>Prendas de vestir</h1>
                    <p class="d-none d-sm-block mt-4">Explora y descubre una amplia variedad de estilos y tendencias. Desde ropa casual hasta atuendos formales, tenemos algo para cada ocasión. ¡Encuentra tu estilo perfecto con nosotros!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <h5 class="mb-3">CATEGORIAS</h5>
                    <a class="nav-link active d-flex justify-content-between align-items-center" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                        Todos <span class="text-black-50 ml-2">(<?= $numrows ?>)</span>
                    </a>
                    <?php
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM categoria");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        $category_id = $category['catId'];
                        $product_count_query = mysqli_query($con, "SELECT count(*) FROM producto WHERE catId = $category_id");
                        $product_count = mysqli_fetch_row($product_count_query)[0];
                        echo "<a class='nav-link d-flex justify-content-between align-items-center' id='v-pills-{$category['catId']}-tab' data-toggle='pill' href='#v-pills-{$category['catId']}' role='tab' aria-controls='v-pills-{$category['catId']}' aria-selected='false'>{$category['catNombre']} <span class='text-black-50 ml-2'>($product_count)</span></a>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="row">
                            <?php //Todas las productos
                            $qs = "SELECT * FROM producto ORDER BY proId DESC LIMIT 6";
                            $r1 = mysqli_query($con, $qs);

                            while ($rod = mysqli_fetch_array($r1)) {
                                $id = "$rod[proId]";
                                $name = "$rod[proNombre]";
                                $price = "$rod[proPrecio]";
                                $ufile = "$rod[proImg]";
                                echo "
                                <div class='col-12 col-md-6 col-lg-4 mb-3'>
                                    <!-- Servicio Individual -->
                                   <a href='producto/detalleproducto.php?id=$id' class='hover-products'><div class='single-service color-1 bg-hover hover-bottom text-center' style='padding:5px 15px 15px'>
                                        <img src='../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                                        <h5 class='my-3'>$name</h5>
                                        <p>S/. $price.00</p>
                                    </div>  </a>
                                </div>
                                ";
                            }
                            ?>
                        </div>
                    </div>
                    <?php //Categorias por individual
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM Categoria");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo "<div class='tab-pane fade' id='v-pills-{$category['catId']}' role='tabpanel' aria-labelledby='v-pills-{$category['catId']}-tab'>";
                        echo "<div class='row'>";
                        $qs = "SELECT * FROM Producto WHERE catId = {$category['catId']} ORDER BY proId DESC LIMIT 6";
                        $r1 = mysqli_query($con, $qs);

                        while ($rod = mysqli_fetch_array($r1)) {
                            $id = "$rod[proId]";
                            $name = "$rod[proNombre]";
                            $price = "$rod[proPrecio]";
                            $ufile = "$rod[proImg]";

                            echo "
                            <div class='col-12 col-md-6 col-lg-4 res-margin'>
                                <!-- Servicio Individual -->
                                <a href='producto/detalleproducto.php?id=$id' class='hover-products'> <div class='single-service color-1 bg-hover bg-white hover-bottom text-center' style='padding:5px 15px 15px'>
                                    <img src='../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                                    <h5 class='my-3'>$name</h5>
                                    <p>S/. $price.00</p>
                                    <a class='service-btn mt-3' href='producto/detalleproducto.php?id=$id'>Ver descripción</a>
                                </div>
                                </a>
                            </div>
                            ";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Área de Servicios Fin ***** -->

<!-- ***** Área de Portafolio Inicio ***** -->
<section id="portfolio" class="portfolio-area overflow-hidden ptb_100">
    <div class="container">

        <!-- ***** Área de Reseñas Inicio ***** -->
        <section id="review" class="section review-area bg-overlay ptb_100" style="background-color: red;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-7">
                        <!-- Encabezado de Sección -->

                        <div class="section-heading text-center">
                            <h2 class="text-white">Portafolio</h2>
                            <p class="text-white d-none d-sm-block mt-4">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ex error vel laborum optio nulla provident atque iure beatae et dolore! Nostrum, commodi accusamus. Dicta incidunt maiores quisquam, est nam voluptatem.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Reseñas de Clientes -->
                    <div class="client-reviews owl-carousel">
                        <!-- Reseña Individual -->

                        <?php
                        $q = "SELECT * FROM  oferta ORDER BY ofeId DESC LIMIT 6";

                        $r123 = mysqli_query($con, $q);

                        while ($ro = mysqli_fetch_array($r123)) {
                            $name = "$ro[ofeTiempo]";
                            $position = "$ro[ofeTiempo]";
                            $message = "$ro[ofeTiempo]";
                            $ufile = "$ro[ofeTiempo]";

                            echo "

<div class='single-review p-5'>
<!-- Contenido de la Reseña -->
<div class='review-content'>
    <!-- Texto de la Reseña -->
    <div class='review-text'>
    <p>$message</p>
    </div>
    <!-- Icono de Cita -->

</div>
<!-- Reseñador -->
<div class='reviewer media mt-3'>
    <!-- Imagen del Reseñador -->
    <div class='reviewer-thumb'>
    <img class='avatar-lg radius-100' src='../paneladministrador/recursos/uploads/producto/$ufile' alt='img'>
    </div>
    <!-- Media del Reseñador -->
    <div class='reviewer-meta media-body align-self-center ml-4'>
    <h5 class='reviewer-name color-primary mb-2'>$name</h5>
    <h6 class='text-secondary fw-6'>$position</h6>
    </div>
</div>
</div>
";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Área de Reseñas Fin ***** -->

        <?php include "footer.php"; ?>