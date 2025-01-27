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
$product_category_id = $tr['catId'];

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
                <div class="product-image text-center d-flex align-items-center justify-content-center">
                    <?php if ($product_image2): ?>
                        <img src="../../paneladministrador/recursos/uploads/producto/<?php echo $product_image2; ?>" alt="img" class="img-fluid mr-2 small-image" style="max-width: 100px;">
                    <?php endif; ?>
                    <div class="main-image-container" style="overflow: hidden; margin-left: 20px;">
                        <img src="../../paneladministrador/recursos/uploads/producto/<?php echo $product_image; ?>" alt="img" class="img-fluid main-image" style="width: 480px; max-width: 480px;">
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5 d-flex position-relative" style="border-left: 1px solid #ddd;">
                <div class="product-content section-heading text-lg-left pl-md-1 mt-lg-0 mb-0 w-100">
                    <p class="text-muted"><?php echo $product_brand; ?></p>
                    <h2 class="nombre-producto"><?php echo $product_name; ?></h2>
                    <p class="text-dark mt-2"><?php echo $product_desc; ?></p>
                     <hr class="mt-2">
                    <h3> S/. <?php echo $product_price; ?></h3>
                    <!-- Colores disponibles -->
                    <div class="mb-4 mt-4">
                        <label class="form-label">Color: <span id="selected-color-name"><?php echo mysqli_fetch_assoc($colors)['colNombre']; ?></span></label>
                        <div class="d-flex align-items-center">
                            <?php 
                            mysqli_data_seek($colors, 0);
                            while ($color = mysqli_fetch_assoc($colors)): ?>
                                <div class="color-circle" title="<?php echo $color['colNombre']; ?>" style="background-color: <?php echo $color['colCodigoHex']; ?>; width: 30px; height: 30px; border: 3px solid white; border-radius: 50%; margin-right: 10px; cursor: pointer;" onclick="selectColor('<?php echo $color['colNombre']; ?>', this)"></div>
                            <?php endwhile; ?>
                        </div>
                    </div> 
                    <!-- Tallas disponibles -->
                    <div class="mb-4">
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

<!-- ***** Productos relacionados(sugerencias) inicio ***** -->
<section class="section related-products ptb_50">
    <div class="container">
        <div class="row" style="height: 90px;">
            <div class="col-12">
            <div class="section-heading">
                <h3 class="text-muted">Más opciones similares</h3>
                <hr>
            </div>
            </div>
        </div>
      
        <div class="row">
            <?php
            $related_products = mysqli_query($con, "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg,m.marNombre FROM Producto p
                                                    INNER JOIN stock s ON p.proId = s.proId
                                                    INNER JOIN marca m on m.marId=p.marId
                                                    WHERE p.catId = '$product_category_id' AND p.proId != '$todo' AND s.stoCantidad > 0
                                                    ORDER BY p.proId DESC LIMIT 4");
            while ($related = mysqli_fetch_assoc($related_products)): ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                <a href="detalleproducto.php?id=<?php echo $related['proId']; ?>" class='hover-products'>  <div class='single-service color-1 bg-hover bg-white hover-bottom text-center p-3'>
                        <img src="../../paneladministrador/recursos/uploads/producto/<?php echo $related['proImg']; ?>" alt="img" class="img-fluid">
                        <p class='text-muted font-italic mt-2'><?php echo $related['marNombre']; ?></p>
                        <h5 class="mb-2"><?php echo $related['proNombre']; ?></h5>
                        <p>S/. <?php echo $related['proPrecio']; ?></p>
                    </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
</section>
<?php include "../footer.php"; ?>