<?php
include "../header.php";
$todo = mysqli_real_escape_string($con, $_GET["id"]);

$rt = mysqli_query($con, "SELECT p.*, c.catNombre, m.marNombre, m.marImg FROM Producto p 
                          INNER JOIN Categoria c ON p.catId = c.catId 
                          INNER JOIN Marca m ON p.marId = m.marId 
                          WHERE p.proId='$todo'");
$tr = mysqli_fetch_array($rt);
$product_name = $tr['proNombre'];
$product_desc = $tr['proDescripcion'];
$product_price = $tr['proPrecio'];
$product_image = $tr['proImg'];
$product_image2 = $tr['proImg2'];
$product_category = $tr['catNombre'];
$product_brand = $tr['marNombre'];
$product_brand_img = $tr['marImg'];
?>

<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                    <h2 class="text-uppercase mb-3 mt-3 text-opacity20">Detalles del producto</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ***** Productos detalle inicio ***** -->
<section class="section product-detail-area ptb_100">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-lg-6">
                <div class="product-image text-center">
                    <img src="../../paneladministrador/recursos/uploads/producto/<?php echo $product_image; ?>" alt="img" class="img-fluid">
                    <?php if ($product_image2): ?>
                        <img src="../../paneladministrador/recursos/uploads/producto/<?php echo $product_image2; ?>" alt="img" class="img-fluid mt-3">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="product-content section-heading text-center text-lg-left pl-md-4 mt-5 mt-lg-0 mb-0">
                    <h2 class="mb-3"><?php echo $product_name; ?></h2>
                    <p class="text-muted mb-3"><?php echo $product_desc; ?></p>
                    <p class="text-dark mb-3 mt-4"><strong>Precio:</strong> S/. <?php echo $product_price; ?>.00</p>
                    <p class="text-dark mb-3"><strong>Categoría:</strong> <?php echo $product_category; ?></p>
                    <p class="text-dark mb-3"><strong>Marca:</strong> <?php echo $product_brand; ?></p>
                    <?php if ($product_brand_img): ?>
                        <img src="../../paneladministrador/recursos/uploads/marca/<?php echo $product_brand_img; ?>" alt="img" class="img-fluid mt-3 text-opacity20">
                    <?php endif; ?>
                    <!-- Buttons -->
                    <div class="button-group mt-5">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <a href="addtocart.php?id=<?php echo $todo; ?>" class="btn btn-block btn-bordered-black p-3 mb-1">Añadir a la cesta</a>
                            </div>
                            <div class="col-12">
                                <a href="checkout.php?id=<?php echo $todo; ?>" class="btn btn-block p-3 ">Comprar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Productos detalle fin***** -->

<!-- ***** Inicio área contacto ***** -->
<section id="contact" class="contact-area ptb_100">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-lg-5">
                <div class="section-heading text-center mb-3">
                    <h2>Contáctanos</h2>
                    <p class="d-none d-sm-block mt-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus sit quisquam voluptas adipisci reprehenderit laudantium temporibus eveniet, exercitationem dolorem voluptatum itaque nostrum rerum ipsum dicta quod error, sunt modi! Dolorem?</p>
                </div>
                <!-- Contáctanos -->
                <div class="contact-us">
                    <ul>
                        <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-3">
                            <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                            <a class="d-block my-2" href="tel:<?php print $conTelefono ?>">
                                <h3>+51 <?php print $conTelefono ?></h3>
                            </a>
                        </li>
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
<?php include "../footer.php"; ?>