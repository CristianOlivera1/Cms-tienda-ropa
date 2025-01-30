<?php include "header.php"; ?>

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
                    <a class="nav-link active d-flex justify-content-between align-items-center" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" onclick="loadProducts('home')">
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
                            echo "<a class='nav-link d-flex justify-content-between align-items-center' id='v-pills-{$category['catId']}-tab' data-toggle='pill' href='#v-pills-{$category['catId']}' role='tab' aria-controls='v-pills-{$category['catId']}' aria-selected='false' onclick=\"loadProducts('{$category['catId']}')\">{$category['catNombre']}  <small class='text-black-50 ml-2'>($product_count)</small></a>";
                        }
                    }
                    ?>
                </div>
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
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM Categoria");
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

<script>
function loadProducts(category) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `cargar-productos.php?category=${category}`, true);
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            document.getElementById(`product-list-${category}`).innerHTML = response.products;
            // Initialize Swiper
            new Swiper(`#swiper-${category}`, {
                slidesPerView: 4,
                spaceBetween: 10,
                grid: {
                    rows: 2, // Mostrar 2 filas
                    fill: 'row',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        grid: {
                            rows: 2,
                        },
                        spaceBetween: 6,
                    },
                    768: {
                        slidesPerView: 2,
                        grid: {
                            rows: 2,
                        },
                        spaceBetween: 12,
                    },
                    1024: {
                        slidesPerView: 4,
                        grid: {
                            rows: 2,
                        },
                        spaceBetween: 18,
                    },
                }
            });
        }
    };
    xhr.send();
}

// Cargar productos iniciales para la categoría "Todos"
document.addEventListener('DOMContentLoaded', function() {
    loadProducts('home');
});
</script>

<?php include "footer.php"; ?>