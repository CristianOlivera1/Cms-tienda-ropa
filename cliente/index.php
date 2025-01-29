<?php include "header.php"; ?>

<?php
$rr = mysqli_query($con, "SELECT * FROM portada");
$r = mysqli_fetch_row($rr);
$porTitulo = $r[1];
$porDescripcion = $r[2];

$result = mysqli_query($con, "SELECT COUNT(DISTINCT p.proId) FROM producto p
                              INNER JOIN stock s ON p.proId = s.proId
                              WHERE s.stoCantidad > 0");
$row = mysqli_fetch_row($result);
$numrows = $row[0];

$sale_products_query = "SELECT DISTINCT p.proId FROM oferta o INNER JOIN stock s ON o.stoId = s.stoId INNER JOIN producto p ON s.proId = p.proId WHERE o.ofeTiempo > NOW() AND s.stoCantidad > 0
";
$sale_products_result = mysqli_query($con, $sale_products_query);
$sale_product_ids = [];
while ($sale_product = mysqli_fetch_assoc($sale_products_result)) {
    $sale_product_ids[] = $sale_product['proId'];
}
$sale_product_ids_str = implode(',', $sale_product_ids);
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
                        <a href="/cliente/categoria/todascategorias.php" class="btn btn-bordered-white d-none d-sm-inline-block">Ver categorías</a>
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
            <div class="col-2">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <h5 class="mb-3">CATEGORIAS</h5>
                    <a class="nav-link active d-flex justify-content-between align-items-center" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                        Todos <small class="text-black-50 ml-2"> (<?= $numrows ?>)</small>
                    </a>
                    <?php
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM categoria");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        $category_id = $category['catId'];
                        $product_count_query = "SELECT COUNT(DISTINCT p.proId) FROM producto p INNER JOIN stock s ON p.proId = s.proId
                                                WHERE s.stoCantidad > 0 AND p.catId = $category_id";
                        if (!empty($sale_product_ids_str)) {
                            $product_count_query .= " AND p.proId NOT IN ($sale_product_ids_str)";
                        }
                        $product_count_result = mysqli_query($con, $product_count_query);
                        $product_count = mysqli_fetch_row($product_count_result)[0];
                        if ($product_count > 0) {
                            echo "<a class='nav-link d-flex justify-content-between align-items-center' id='v-pills-{$category['catId']}-tab' data-toggle='pill' href='#v-pills-{$category['catId']}' role='tab' aria-controls='v-pills-{$category['catId']}' aria-selected='false'>{$category['catNombre']}  <small class='text-black-50 ml-2'>($product_count)</small></a>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-10">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="row">
                            <?php 
                            $qs = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre FROM producto p
                                   INNER JOIN stock s ON p.proId = s.proId INNER JOIN marca m on m.marId=p.marId
                                   WHERE s.stoCantidad > 0";
                            if (!empty($sale_product_ids_str)) {
                                $qs .= " AND p.proId NOT IN ($sale_product_ids_str)";
                            }
                            $qs .= " ORDER BY p.proId DESC LIMIT 6";
                            $r1 = mysqli_query($con, $qs);

                            while ($rod = mysqli_fetch_array($r1)) {
                                $id = "$rod[proId]";
                                $name = "$rod[proNombre]";
                                $price = "$rod[proPrecio]";
                                $ufile = "$rod[proImg]";
                                $marNombre = "$rod[marNombre]";
                                
                                echo "
                                <div class='col-12 col-md-6 col-lg-4'>
                                    <a href='producto/detalleproducto.php?id=$id' class='hover-products'>
                                        <div class='single-service color-1 bg-hover hover-bottom text-center' style='padding:5px 15px 15px'>
                                            <img src='../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                                            <p class='text-muted font-italic mt-2'>$marNombre</p>
                                            <h5 class='my-1'>$name</h5>
                                            <p>S/ $price</p>
                                        </div>
                                    </a>
                                </div>
                                ";
                            }
                            ?>
                        </div>
                    </div>
                    <?php 
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM Categoria");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo "<div class='tab-pane fade' id='v-pills-{$category['catId']}' role='tabpanel' aria-labelledby='v-pills-{$category['catId']}-tab'>";
                        echo "<div class='row'>";
                        $qs = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg,m.marNombre  FROM Producto p
                               INNER JOIN stock s ON p.proId = s.proId INNER JOIN marca m on m.marId=p.marId
                               WHERE s.stoCantidad > 0 AND p.catId = {$category['catId']}";
                        if (!empty($sale_product_ids_str)) {
                            $qs .= " AND p.proId NOT IN ($sale_product_ids_str)";
                        }
                        $qs .= " ORDER BY p.proId DESC LIMIT 6";
                        $r1 = mysqli_query($con, $qs);

                        while ($rod = mysqli_fetch_array($r1)) {
                            $id = "$rod[proId]";
                            $name = "$rod[proNombre]";
                            $price = "$rod[proPrecio]";
                            $ufile = "$rod[proImg]";
                            $marNombre = "$rod[marNombre]";

                            echo "
                            <div class='col-12 col-md-6 col-lg-4 res-margin'>
                                <a href='producto/detalleproducto.php?id=$id' class='hover-products'> 
                                    <div class='single-service color-1 bg-hover bg-white hover-bottom text-center' style='padding:5px 15px 15px'>
                                        <img src='../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                                        <p class='text-muted font-italic mt-2'>$marNombre</p>
                                        <h5 class='my-1'>$name</h5>
                                        <p>S/ $price.00</p>
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

<section id="portfolio" class="portfolio-area overflow-hidden ptb_100">
    <div class="container">
        <section id="review" class="section review-area bg-overlay ptb_100" style="background-color: red;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-7">
                        <div class="section-heading text-center">
                            <h2 class="text-white">Ofertas</h2>
                            <p class="text-white d-none d-sm-block mt-4">Explora nuestras ofertas exclusivas en productos seleccionados.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php
                    // Consulta para obtener productos en oferta y disponibles en stock
                    $ofertas_query = mysqli_query($con, "
                        SELECT p.proId, p.proNombre, p.proPrecio, p.proImg, o.ofePorcentaje, s.stoCantidad
                        FROM oferta o
                        INNER JOIN stock s ON o.stoId = s.stoId
                        INNER JOIN producto p ON s.proId = p.proId
                        WHERE o.ofeTiempo > NOW() AND s.stoCantidad > 0
                    ");
                    while ($oferta = mysqli_fetch_assoc($ofertas_query)) {
                        $id = $oferta['proId'];
                        $name = $oferta['proNombre'];
                        $price = $oferta['proPrecio'];
                        $img = $oferta['proImg'];
                        $discount = $oferta['ofePorcentaje'];
                        // Calcular el precio con descuento
                        $discountedPrice = $price - ($price * ($discount / 100));

                        echo "
                        <div class='col-12 col-md-6 col-lg-4 mb-3'>
                            <a href='producto/detalleofertaproducto.php?id=$id' class='hover-products'>
                                <div class='single-service color-1 bg-hover hover-bottom text-center' style='padding:5px 15px 15px'>
                                    <img src='../../paneladministrador/recursos/uploads/producto/$img' alt='img' class='category-img'>
                                    <h5 class='mb-2'>$name</h5>
                                    <p class='text-muted'><s>S/. $price</s> S/. " . number_format($discountedPrice, 2) . "</p>
                                    <p class='text-success'>Descuento: $discount%</p>
                                </div>
                            </a>
                        </div>
                        ";
                    }
                    ?>
                </div>
            </div>
        </section>
        </div>
</section>
        <?php include "footer.php"; ?>