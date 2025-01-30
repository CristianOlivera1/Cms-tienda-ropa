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
                            $selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
                            if ($selectedCategory) {
                                $category_query = mysqli_query($con, "SELECT catNombre FROM Categoria WHERE catId = $selectedCategory");
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
            <img src="../recursos/img/welcome/section-categorias.svg" alt="section-categorias">
        </div>
    </div>
</section>

<!-- ***** Área de Servicios Inicio ***** -->
<section id="service" class="section service-area bg-grey ptb_150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="section-heading text-center">
                    <h1>Categorias</h1>
                    <p class="d-none d-sm-block mt-4">Explora y descubre una amplia variedad de estilos y tendencias. Desde ropa casual hasta atuendos formales, tenemos algo para cada ocasión. ¡Encuentra tu estilo perfecto con nosotros!</p>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <h5 class="mb-3">CATEGORIAS</h5>
            <a class="nav-link <?php echo $selectedCategory == '' ? 'active' : ''; ?> d-flex justify-content-between align-items-center" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                Todos <small class='text-black-50 ml-2'>( <?= $numrows ?>)</small>
            </a>
            <?php
            $categories = mysqli_query($con, "SELECT catId, catNombre FROM categoria order by catNombre asc");
            while ($category = mysqli_fetch_assoc($categories)) {
                $category_id = $category['catId'];
                $product_count_query = mysqli_query($con, "SELECT COUNT(DISTINCT p.proId) FROM producto p
                                      INNER JOIN stock s ON p.proId = s.proId
                                      WHERE s.stoCantidad > 0 AND p.catId = $category_id");
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
            <div class="tab-pane fade <?php echo $selectedCategory == '' ? 'show active' : ''; ?>" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <div class="row">
                <?php //Todas los productos
                $qs = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre FROM producto p
                       INNER JOIN stock s ON p.proId = s.proId INNER JOIN marca m on m.marId=p.marId
                       WHERE s.stoCantidad > 0
                       ORDER BY p.proId DESC LIMIT 6";
                $r1 = mysqli_query($con, $qs);

                while ($rod = mysqli_fetch_array($r1)) {
                    $id = "$rod[proId]";
                    $name = "$rod[proNombre]";
                    $price = "$rod[proPrecio]";
                    $ufile = "$rod[proImg]";
                    $marNombre = "$rod[marNombre]";
                    
                    echo "
                    <div class='col-12 col-md-6 col-lg-4'>
                    <!-- producto todos -->
                       <a href='detalleproducto.php?id=$id' class='hover-products'><div class='single-service color-1 bg-hover hover-bottom text-center' style='padding:5px 15px 15px'>
                        <img src='../../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                        <p class='text-muted font-italic mt-2'>$marNombre</p>
                        <h5 class='mb-2'>$name</h5>
                        <p>S/. $price</p>
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
                $activeClass = $selectedCategory == $category['catId'] ? 'show active' : '';
                echo "<div class='tab-pane fade $activeClass' id='v-pills-{$category['catId']}' role='tabpanel' aria-labelledby='v-pills-{$category['catId']}-tab'>";
                echo "<div class='row'>";
                $qs = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre FROM Producto p
                   INNER JOIN stock s ON p.proId = s.proId INNER JOIN marca m on m.marId=p.marId
                   WHERE s.stoCantidad > 0 AND p.catId = {$category['catId']}
                   ORDER BY p.proId DESC LIMIT 6";
                $r1 = mysqli_query($con, $qs);

                while ($rod = mysqli_fetch_array($r1)) {
                $id = "$rod[proId]";
                $name = "$rod[proNombre]";
                $price = "$rod[proPrecio]";
                $ufile = "$rod[proImg]";
                $marNombre = "$rod[marNombre]";

                echo "
                <div class='col-12 col-md-6 col-lg-4 res-margin'>
                    <!-- Servicio Individual -->
                    <a href='detalleproducto.php?id=$id' class='hover-products'> <div class='single-service color-1 bg-hover bg-white hover-bottom text-center' style='padding:5px 15px 15px'>
                    <img src='../../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
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
<!-- ***** Área de productos Fin ***** -->
<section class="section cta-area bg-overlay ptb_100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="section-heading text-center m-0">
                    <h2 class="text-black">Otro mas</h2>
                    <p class="text-black d-none d-sm-block mt-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui porro, modi quo ipsum iusto dolorem iste molestiae corrupti sed natus hic odit fuga et, cumque, ratione doloremque dicta quod! Non.</p>
                    <a href="contacto.php" class="btn btn-bordered-black mt-4">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include "../footer.php"; ?>