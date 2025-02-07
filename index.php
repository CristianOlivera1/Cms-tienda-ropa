<?php include "cliente/header.php"; ?>

<?php
$rr = mysqli_query($con, "SELECT * FROM portada");
$r = mysqli_fetch_row($rr);
$porTitulo = $r[1];
$porDescripcion = $r[2];

// Obtener el número total de productos en la categoría "Todos"
$result = mysqli_query($con, "SELECT COUNT(DISTINCT p.proId) FROM producto p INNER JOIN stock s ON p.proId = s.proId WHERE s.stoCantidad > 0");
$row = mysqli_fetch_row($result);
$numrows = $row[0];

$sale_products_query = "SELECT DISTINCT p.proId FROM oferta o INNER JOIN stock s ON o.stoId = s.stoId INNER JOIN producto p ON s.proId = p.proId WHERE o.ofeTiempo > NOW() AND s.stoCantidad > 0";
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
                    <p class="white-70 my-4 lead mt-4" style="font-size: 1.2em;"><?php echo $porDescripcion ?></p>
                    <div class="button-group">
                        <a href="cliente/producto/producto.php" class="btn btn-bordered-white">Ver productos</a>
                        <a href="/cliente/categoria/todascategorias.php" class="btn btn-bordered-white d-none d-sm-inline-block">Ver categorías</a>
                    </div>
                </div>
            </div>
            <!-- slider-->
            <div class="col-12 col-md-5">
                <div class="swiper-container" id="product-carousel">
                    <div class="swiper-wrapper">
                        <?php
                        $product_query = "SELECT p.proId, p.proNombre, p.proImg, m.marNombre, p.proPrecio FROM producto p 
                                          INNER JOIN stock s ON p.proId = s.proId 
                                          INNER JOIN marca m ON m.marId = p.marId 
                                          INNER JOIN estado e ON e.estId = s.estId 
                                          WHERE s.stoCantidad > 0 AND e.estDisponible = 'Disponible' 
                                          GROUP BY p.proId 
                                          ORDER BY RAND()";
                        $product_result = mysqli_query($con, $product_query);
                        while ($product = mysqli_fetch_assoc($product_result)) {
                            echo "
                <div class='swiper-slide w-75'>
                    <a href='cliente/producto/detalleproducto.php?id={$product['proId']}' class='product-link'>
                        <div class='product-card'>
                            <div class='image-container'>
                                <img class='product-image' src='paneladministrador/recursos/uploads/producto/{$product['proImg']}' alt='{$product['proNombre']}'>
                            </div>
                            <div class='product-info'>
                                <h5>{$product['marNombre']}</h5>
                                <h4>{$product['proNombre']}</h4>
                                <p class='price'>S/ {$product['proPrecio']}</p>
                            </div>
                        </div>
                    </a>
                </div>";
            }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="shape shape-bottom">
        <img src="cliente/recursos/img/welcome/fondo-portada.svg" alt="fondo-portada">
    </div>
</section>

<section id="service" class="section service-area bg-grey ptb_150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="section-heading text-center">
                    <h1>Prendas de vestir</h1>
                    <p class="lead mt-4 text-secondary">Explora y descubre una amplia variedad de estilos y tendencias. Desde ropa casual hasta atuendos formales, tenemos algo para cada ocasión. ¡Encuentra tu estilo perfecto con nosotros!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <h5 class="mb-3">CATEGORIAS</h5>
                    <a class="nav-link active d-flex justify-content-between align-items-center" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" onclick="loadProducts('home', getSelectedBrand())">
                        Todos <small class="text-black-50 ml-2"> (<?= $numrows ?>)</small>
                    </a>
                    <?php
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM categoria order by catNombre asc");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        $category_id = $category['catId'];
                        $product_count_query = "SELECT COUNT(DISTINCT p.proId) FROM producto p INNER JOIN stock s ON p.proId = s.proId WHERE s.stoCantidad > 0 AND p.catId = $category_id";
                        if (!empty($sale_product_ids_str)) {
                            $product_count_query .= " AND p.proId NOT IN ($sale_product_ids_str)";
                        }
                        $product_count_result = mysqli_query($con, $product_count_query);
                        $product_count = mysqli_fetch_row($product_count_result)[0];
                        if ($product_count > 0) {
                            echo "<a class='nav-link d-flex justify-content-between align-items-center' id='v-pills-{$category['catId']}-tab' data-toggle='pill' href='#v-pills-{$category['catId']}' role='tab' aria-controls='v-pills-{$category['catId']}' aria-selected='false' onclick=\"loadProducts('{$category['catId']}', getSelectedBrand())\">{$category['catNombre']}  <small class='text-black-50 ml-2'>($product_count)</small></a>";
                        }
                    }
                    ?>
                </div>
                <!-- select aqui para filtrar por marca -->
            <h5 class="mt-4 bg-white p-2">MARCAS</h5>
            <select class="form-select w-100 select-marca" id="brand-filter" onchange="filterByBrand(this.value)">
                <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Todas</option>
                <?php
                $brands = mysqli_query($con, "SELECT DISTINCT marNombre FROM marca ORDER BY marNombre ASC");
                while ($brand = mysqli_fetch_assoc($brands)) {
                    echo "<option value='{$brand['marNombre']}' class='ml-2' >{$brand['marNombre']}</option>";
                }
                ?>
            </select>

            </div>
            <div class="col-10">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="swiper-container" id="swiper-home">
                            <div class="swiper-wrapper" id="product-list-home">
                                <!-- Productos cargados dinámicamente -->
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                    <?php
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM categoria");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo "<div class='tab-pane fade' id='v-pills-{$category['catId']}' role='tabpanel' aria-labelledby='v-pills-{$category['catId']}-tab'>";
                        echo "<div class='swiper-container' id='swiper-{$category['catId']}'>";
                        echo "<div class='swiper-wrapper' id='product-list-{$category['catId']}'>";
                        // Productos cargados dinámicamente
                        echo "</div>";
                        echo "<div class='swiper-button-next'></div>";
                        echo "<div class='swiper-button-prev'></div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<?php include "cliente/scroll-marcas.php"; ?>
<?php include "cliente/footer.php"; ?>