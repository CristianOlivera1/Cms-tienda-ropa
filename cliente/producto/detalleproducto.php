<?php
include "../header.php";
$todo = mysqli_real_escape_string($con, $_GET["id"]);

// Obtener detalles del producto
$rt = mysqli_query($con, "SELECT p.*, c.catNombre, m.marNombre, m.marImg FROM Producto p 
                          INNER JOIN categoria c ON p.catId = c.catId 
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

// Obtener colores y tallas disponibles
$colors = mysqli_query($con, "SELECT DISTINCT c.colNombre, c.colCodigoHex FROM stock s 
                              INNER JOIN color c ON s.colId = c.colId 
                              WHERE s.proId='$todo'");
$tallas = mysqli_query($con, "SELECT DISTINCT t.talNombre FROM stock s 
                              INNER JOIN talla t ON s.talId = t.talId 
                              WHERE s.proId='$todo'");

// Obtener cantidad de stock disponible
$stock = mysqli_query($con, "SELECT SUM(stoCantidad) as totalCantidad FROM stock WHERE proId='$todo'");
$stock_data = mysqli_fetch_array($stock);
$stock_quantity = $stock_data['totalCantidad'];
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
        <div class="row justify-content-between">
            <div class="col-12 col-lg-7 d-flex" style="position: relative; top: 0;">
                <div class="product-image text-center">
                    <?php if ($product_image2): ?>
                        <img src="../../paneladministrador/recursos/uploads/producto/<?php echo $product_image2; ?>" alt="img" class="img-fluid mr-2" style="max-width: 100px;">
                    <?php endif; ?>
                    <img src="../../paneladministrador/recursos/uploads/producto/<?php echo $product_image; ?>" alt="img" class="img-fluid" style="width: 480px; max-width: 480px;">
                </div>
            </div>
            <div class="col-12 col-lg-5 d-flex position-relative">
                <div class="product-content section-heading text-lg-left pl-md-1 mt-lg-0 mb-0">
                    <p class="text-muted"><?php echo $product_brand; ?></p>
                    <h2 class="nombre-producto"><?php echo $product_name; ?></h2>
                    <p class="text-dark mb-3 mt-2"><?php echo $product_desc; ?></p>
                    <h3> S/. <?php echo $product_price; ?></h3>
                    <!-- Colores disponibles -->
                    <div class="mb-3 mt-4">
                        <label class="form-label">Color: <span id="selected-color-name"><?php echo mysqli_fetch_assoc($colors)['colNombre']; ?></span></label>
                        <div class="d-flex align-items-center">
                            <?php 
                            mysqli_data_seek($colors, 0); // Reset the pointer to the beginning
                            while ($color = mysqli_fetch_assoc($colors)): ?>
                                <div class="color-circle" title="<?php echo $color['colNombre']; ?>" style="background-color: <?php echo $color['colCodigoHex']; ?>; width: 30px; height: 30px; border: 3px solid white; border-radius: 50%; margin-right: 10px; cursor: pointer;" onclick="selectColor('<?php echo $color['colNombre']; ?>', this)"></div>
                            <?php endwhile; ?>
                        </div>
                    </div> 
                    <!-- Tallas disponibles -->
                    <div class="mb-3">
                        <label for="talla" class="form-label">Talla:</label> <br>
                        <select id="talla" class="form-select w-100" style="height: 45px;">
                            <?php while ($talla = mysqli_fetch_assoc($tallas)): ?>
                                <option value="<?php echo $talla['talNombre']; ?>">&nbsp;&nbsp;<?php echo $talla['talNombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <!-- Cantidad -->
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <div class="input-group input-group-sm d-flex align-items-center">
                            <button class="btn-mutted" type="button" id="decrement">-</button>
                            <input type="number" id="cantidad" class="form-control text-center mx-2" value="1" min="1" max="<?php echo $stock_quantity; ?>" style="max-width: 60px;">
                            <button class="btn-mutted" type="button" id="increment">+</button>
                        </div>
                        <small class="form-text text-muted">Stock disponible: <?php echo $stock_quantity; ?></small>
                    </div>
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
                <div class="brand-image position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; opacity: 0.05; z-index: -1;">
                    <img src="../../paneladministrador/recursos/uploads/marca/<?php echo $product_brand_img; ?>" alt="brand" class="img-fluid" style="width: 300px; height: auto;">
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