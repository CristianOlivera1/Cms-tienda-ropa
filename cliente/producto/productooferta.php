<?php include "../header.php";

$result = mysqli_query($con, "SELECT COUNT(DISTINCT p.proId) FROM producto p INNER JOIN stock s ON p.proId = s.proId INNER JOIN oferta o ON s.stoId = o.stoId WHERE s.stoCantidad > 0 AND o.ofeTiempo > NOW()");
$row = mysqli_fetch_row($result);
$numOffers = $row[0];

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Pagination setup
$limit = 20; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Calculate offset

// Query for total number of offers
$totalOffersQuery = mysqli_query($con, "SELECT COUNT(DISTINCT p.proId) FROM producto p 
                                          INNER JOIN stock s ON p.proId = s.proId 
                                          INNER JOIN oferta o ON s.stoId = o.stoId 
                                          WHERE s.stoCantidad > 0 AND o.ofeTiempo > NOW()");
$totalOffers = mysqli_fetch_row($totalOffersQuery)[0];
$totalPages = ceil($totalOffers / $limit); // Total pages

?>

<section class="align-items-center-categories">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-categories text-center">
                    <h1 class="text-white text-uppercase mb-3"><strong> Nuestras ofertas </strong></h1>
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

<!-- ***** Área de Ofertas Inicio ***** -->
<section id="offer" class="section offer-area bg-grey ptb_150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="section-heading text-center">
                    <h1>Ofertas Disponibles</h1>
                    <p class="d-none d-sm-block mt-4">Aprovecha nuestras increíbles ofertas en productos seleccionados. ¡No te las pierdas!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <h5 class="mb-3">CATEGORIAS</h5>
                    <a class="nav-link <?php echo $selectedCategory == '' ? 'active' : ''; ?> d-flex justify-content-between align-items-center" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                        Todos <small class='text-black-50 ml-2'>( <?= $numOffers ?>)</small>
                    </a>
                    <?php
                    $categories = mysqli_query($con, "SELECT catId, catNombre FROM categoria order by catNombre asc");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        $category_id = $category['catId'];
                        $product_count_query = mysqli_query($con, "SELECT COUNT(DISTINCT p.proId) FROM producto p
                                          INNER JOIN stock s ON p.proId = s.proId
                                          WHERE s.stoCantidad > 0 AND p.catId = $category_id and s.estId = 3");
                        $product_count = mysqli_fetch_row($product_count_query)[0];
                        if ($product_count > 0) {
                            $activeClass = $selectedCategory == $category_id ? 'active' : '';
                            echo "<a class='nav-link d-flex justify-content-between align-items-center $activeClass' id='v-pills-{$category['catId']}-tab' data-toggle='pill' href='#v-pills-{$category['catId']}' role='tab' aria-controls='v-pills-{$category['catId']}' aria-selected='false'>{$category['catNombre']} <small class='text-black-50 ml-2'>($product_count)</small></a>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade <?php echo $selectedCategory == '' ? 'show active' : ''; ?>" id="v-pills-all" role="tabpanel" aria-labelledby="v-pills-all-tab">
                        <div class="row">
                            <?php
                            $qs = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre, o.ofePorcentaje 
                                    FROM producto p 
                                    INNER JOIN stock s ON p.proId = s.proId 
                                    INNER JOIN oferta o ON s.stoId = o.stoId 
                                    INNER JOIN marca m ON m.marId = p.marId 
                                    WHERE s.stoCantidad > 0 AND o.ofeTiempo > NOW()
                                    ORDER BY p.proId DESC LIMIT $limit OFFSET $offset";
                            $r1 = mysqli_query($con, $qs);

                            while ($rod = mysqli_fetch_array($r1)) {
                                $id = "$rod[proId]";
                                $name = "$rod[proNombre]";
                                $price = "$rod[proPrecio]";
                                $ufile = "$rod[proImg]";
                                $marNombre = "$rod[marNombre]";
                                $discount = $rod['ofePorcentaje'];
                                $finalPrice = $price - ($price * $discount / 100);

                                echo "
                                <div class='col-12 col-md-6 col-lg-4 mb-4'>
                                    <a href='detalleofertaproducto.php?id=$id' class='hover-products'>
                                        <div class='single-service color-1 bg-hover hover-bottom text-center' style='padding:5px 15px 15px'>
                                            <img src='../../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                                            <p class='text-muted font-italic mt-2'>$marNombre</p>
                                            <h5 class='mb-2'>$name</h5>
                                            <p class='text-danger'>S/. $finalPrice <small class='text-muted'><del>S/. $price</del> ($discount% OFF)</small></p>
                                        </div>
                                    </a>
                                </div>
                                ";
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    // Offers by category
                    $offerCategories = mysqli_query($con, "SELECT catId, catNombre FROM categoria");
                    while ($category = mysqli_fetch_assoc($offerCategories)) {
                        $activeClass = $selectedCategory == $category['catId'] ? 'show active' : '';
                        echo "<div class='tab-pane fade $activeClass' id='v-pills-{$category['catId']}' role='tabpanel' aria-labelledby='v-pills-{$category['catId']}-tab'>";
                        echo "<div class='row'>";
                        $qs = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre, o.ofePorcentaje 
                                FROM producto p 
                                INNER JOIN stock s ON p.proId = s.proId 
                                INNER JOIN oferta o ON s.stoId = o.stoId 
                                INNER JOIN marca m ON m.marId = p.marId 
                                WHERE s.stoCantidad > 0 AND o.ofeTiempo > NOW() AND p.catId = {$category['catId']}
                                ORDER BY p.proId DESC LIMIT $limit OFFSET $offset";
                        $r1 = mysqli_query($con, $qs);

                        while ($rod = mysqli_fetch_array($r1)) {
                            $id = "$rod[proId]";
                            $name = "$rod[proNombre]";
                            $price = $rod['proPrecio'];
                            $ufile = $rod['proImg'];
                            $marNombre = "$rod[marNombre]";
                            $discount = $rod['ofePorcentaje'];
                            $finalPrice = $price - ($price * $discount / 100);

                            echo "
                            <div class='col-12 col-md-6 col-lg-4 res-margin'>
                                <a href='detalleofertaproducto.php?id=$id' class='hover-products'>
                                    <div class='single-service color-1 bg-hover bg-white hover-bottom text-center' style='padding:5px 15px 15px'>
                                        <img src='../../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                                        <p class='text-muted font-italic mt-2'>$marNombre</p>
                                        <h5 class='my-1'>$name</h5>
                                        <p class='text-danger'>S/. $finalPrice <small class='text-muted'><del>S/. $price</del> ($discount% OFF)</small></p>
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

        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn btn-bordered-black">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn btn-bordered-black <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn btn-bordered-black">Next</a>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- ***** Área de Ofertas Fin ***** -->
<?php include "../footer.php"; ?>