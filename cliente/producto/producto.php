<?php include "../header.php";

$result = mysqli_query($con, "SELECT COUNT(DISTINCT p.proId) FROM producto p INNER JOIN stock s ON p.proId = s.proId WHERE s.stoCantidad > 0");
$row = mysqli_fetch_row($result);
$numrows = $row[0];

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
?>
<section class="align-items-center-categories">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-categories text-center">
                    <h1 class="text-white text-uppercase mb-3"><strong> LO QUE OFRECEMOS </strong></h1>
                    <ol class="breadcrumb d-flex justify-content-center white-70">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item text-white active">Categorias</li>
                        <li class="breadcrumb-item text-white active">
                            <?php
                            if ($selectedCategory) {
                                $category_query = mysqli_query($con, "SELECT catNombre FROM categoria WHERE catId = $selectedCategory");
                                $category = mysqli_fetch_assoc($category_query);
                                echo $category['catNombre'];
                            }
                            ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="fond-section-categories">
            <img src="../recursos/img/welcome/section-categories.png" alt="section-categorias">
        </div>
    </div>
</section>

<!-- ***** Área de Servicios Inicio ***** -->
<section id="service" class="section service-area bg-grey ptb_150">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="section-heading text-center">
                    <h1 class="display-4 font-weight-bold text-black">Categorías</h1>
                    <p class="lead mt-4 text-secondary">Descubre nuestra colección exclusiva de moda. Desde lo clásico hasta lo contemporáneo, ofrecemos prendas que se adaptan a tu estilo único. ¡Renueva tu guardarropa con nuestras últimas tendencias!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <h5 class="mb-3">CATEGORIAS</h5>
                    <a class="nav-link <?php echo $selectedCategory == '' ? 'active' : ''; ?> d-flex justify-content-between align-items-center" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" onclick="loadProducts('home', getSelectedBrand())">
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
                        $activeClass = $selectedCategory == $category_id ? 'active' : '';
                        if ($product_count > 0) {
                            echo "<a class='nav-link $activeClass d-flex justify-content-between align-items-center' id='v-pills-{$category['catId']}-tab' data-toggle='pill' href='#v-pills-{$category['catId']}' role='tab' aria-controls='v-pills-{$category['catId']}' aria-selected='false' onclick=\"loadProducts('{$category['catId']}', getSelectedBrand())\">{$category['catNombre']}  <small class='text-black-50 ml-2'>($product_count)</small></a>";
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
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade <?php echo $selectedCategory == '' ? 'show active' : ''; ?>" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
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
                        $activeClass = $selectedCategory == $category['catId'] ? 'show active' : '';
                        echo "<div class='tab-pane fade $activeClass' id='v-pills-{$category['catId']}' role='tabpanel' aria-labelledby='v-pills-{$category['catId']}-tab'>";
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
function getSelectedBrand() {
    return document.getElementById('brand-filter').value;
}

function filterByBrand(brand) {
    const category = document.querySelector('.nav-link.active').getAttribute('id').replace('v-pills-', '').replace('-tab', '');
    loadProducts(category, brand);
}

function loadProducts(category, brand = '') {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `cargar-productos.php?category=${category}&brand=${brand}`, true);
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            document.getElementById(`product-list-${category}`).innerHTML = response.products;
            // Initialize Swiper
            new Swiper(`#swiper-${category}`, {
                slidesPerView: 4,
                spaceBetween: 0,
                grid: {
                    rows: 2,
                    fill: 'row',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                        grid: {
                            rows: 2,
                        },
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                        grid: {
                            rows: 2,
                        },
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 0,
                        grid: {
                            rows: 2,
                        },
                    },
                }
            });
        }
    };
    xhr.send();
}

// Cargar productos iniciales para la categoría seleccionada o "Todos"
document.addEventListener('DOMContentLoaded', function() {
    const initialCategory = '<?php echo $selectedCategory; ?>' || 'home';
    loadProducts(initialCategory);
});
</script>

<?php include "../scroll-marcas.php"; ?>
<?php include "../footer.php"; ?>