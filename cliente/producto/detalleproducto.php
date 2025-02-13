<?php
include "../header.php";
$todo = mysqli_real_escape_string($con, $_GET["id"]);

// Obtener detalles del producto
$rt = mysqli_query($con, "SELECT p.*, c.catNombre, m.marNombre, m.marImg FROM producto p 
                          INNER JOIN categoria c ON p.catId = c.catId 
                          INNER JOIN marca m ON p.marId = m.marId 
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
$stock = mysqli_query($con, "SELECT s.stoId, s.colId, s.talId, s.stoCantidad, c.colNombre, t.talNombre FROM stock s 
                             INNER JOIN color c ON s.colId = c.colId 
                             INNER JOIN talla t ON s.talId = t.talId 
                             WHERE s.proId='$todo'");
$stock_data = [];
while ($row = mysqli_fetch_assoc($stock)) {
    $stock_data[$row['colNombre']][$row['talNombre']] = [
        'cantidad' => $row['stoCantidad'],
        'stoId' => $row['stoId'] // Usar el stoId real
    ];
}
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
                        <select id="talla" class="form-select w-100" style="height: 45px;" onchange="updateStock()">
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
                            <input type="number" id="cantidad" class="form-control text-center mx-2" value="1" min="1" max="0" style="max-width: 60px;">
                            <button class="btn-mutted" type="button" id="increment">+</button>
                        </div>
                        <small class="form-text text-muted">Stock disponible: <span id="stock-quantity">0</span></small>
                    </div>
                    <div class="button-group mt-5">
                        <div class="row">
                            <div class="col-12 mb-2">
                            <button class="btn btn-block btn-bordered-black p-3 mb-1" onclick="addToCart(<?php echo $todo; ?>, '<?php echo $product_name; ?>', <?php echo $product_price; ?>, document.getElementById('cantidad').value, '<?php echo $product_image; ?>')">Añadir a la cesta</button>
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

<!-- Modal de error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="errorModalLabel">Información</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="errorModalBody">
                <!-- Mensaje de error -->
                Este es un mensaje de error.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- ***** Productos relacionados(sugerencias) inicio ***** -->
<section class="section related-products ptb_50">
    <div class="container mt-5">
         <div class="row" style="height: 90px;">
            <div class="col-12">
            <div class="section-heading">
                <h3 class="text-black">Más opciones similares</h3>
                <hr>
            </div>
            </div>
        </div>        
        <div class="swiper-container">
            <div class="swiper-wrapper">
            <?php
            $related_products = mysqli_query($con, "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre FROM producto p
                                INNER JOIN stock s ON p.proId = s.proId
                                INNER JOIN marca m on m.marId=p.marId
                                WHERE p.catId = '$product_category_id' AND p.proId != '$todo' AND s.stoCantidad > 0
                                ORDER BY p.proId DESC");

            $productos = [];
            while ($related = mysqli_fetch_assoc($related_products)) {
                $productos[] = $related;
            }

            // Dividir en grupos de 4 (2 filas de 2 productos)
            $chunks = array_chunk($productos, 4);

            foreach ($chunks as $grupo) {
                echo '<div class="swiper-slide">';
                echo '<div class="row mr-0">';
                foreach ($grupo as $related) {
                echo "<div class='col-md-6 mr-0'>
                    <a href='detalleproducto.php?id={$related['proId']}' class='hover-products'>
                        <div class='single-service mb-4 color-1 bg-hover bg-white hover-bottom text-center p-3'>
                        <img src='../../paneladministrador/recursos/uploads/producto/{$related['proImg']}' alt='img' class='img-fluid'>
                        <p class='text-muted font-italic mt-2'>{$related['marNombre']}</p>
                        <h5 class='mb-2'>{$related['proNombre']}</h5>
                        <p>S/ {$related['proPrecio']}</p>
                        </div>
                    </a>
                      </div>";
                }
                echo '</div>';
                echo '</div>';
            }
            ?>
            </div>
            <!-- Controles de navegación -->
             <div class="back">
            <div class="swiper-button-prev"></div>
            </div>
            <div class="next">
            <div class="swiper-button-next"></div>
            </div> <br>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<section class="section product-reviews ptb_50">
    <div class="container">
        <div class="row" style="height: 90px;">
            <div class="col-12">
                <div class="section-heading">
                    <h3 class="text-muted">Reseñas de Compras</h3>
                    <hr>
                </div>
            </div>
        </div>
      
        <div class="row">
    <?php
    // Asegúrate de que $todo contenga el stoId del producto actual
    $reviews = mysqli_query($con, "
        SELECT cliente.cliNombre, resenhas.resFechaRegis, resenhas.resMensaje FROM resenhas
        INNER JOIN ventas ON ventas.venId = resenhas.venId
        INNER JOIN cliente ON cliente.cliId = ventas.cliId
        INNER JOIN detalleventa ON detalleventa.venId = ventas.venId
        INNER JOIN stock ON stock.stoId = detalleventa.stoId");

    while ($review = mysqli_fetch_assoc($reviews)): 
        // Simular una calificación aleatoria entre 1 y 5
        $calificacion = rand(1, 5); ?>
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class='single-review color-1  bg-white hover-bottom text-center p-3'>
                
                <h5 class="mb-2"><?php echo $review['cliNombre']; ?></h5>
                <p class='text-muted font-italic mt-2'><?php echo $review['resFechaRegis']; ?></p>
                
                <div class="rating">
                    <?php
                    for ($i = 1; $i <= 5; $i++): 
                        if ($i <= $calificacion): ?>
                            <span>&#9733;</span>
                        <?php else: ?>
                            <span>&#9734;</span> 
                        <?php endif;
                    endfor; ?>
                </div>
                
                <p><?php echo $review['resMensaje']; ?></p>
            </div>
        </div>
    <?php endwhile; ?>
</div>
    </div>
</section>

<script>
  var stockData = <?php echo json_encode($stock_data); ?>;

function selectColor(colorName, element) {
    document.getElementById('selected-color-name').innerText = colorName;
    var circles = document.getElementsByClassName('color-circle');
    for (var i = 0; i < circles.length; i++) {
        circles[i].style.boxShadow = 'none';
    }
    element.style.boxShadow = '0 0 0 2px black';
    updateStock();
}

function updateStock() {
    var selectedColor = document.getElementById('selected-color-name').innerText;
    var selectedTalla = document.getElementById('talla').value;
    var stockQuantity = (stockData[selectedColor] && stockData[selectedColor][selectedTalla]) ? stockData[selectedColor][selectedTalla].cantidad : 0;
    document.getElementById('cantidad').max = stockQuantity;
    document.getElementById('stock-quantity').innerText = stockQuantity;
}

var firstCircle = document.querySelector('.color-circle');
if (firstCircle) {
    selectColor(firstCircle.title, firstCircle);
}
updateStock();
function addToCart(productId, productName, productPrice, quantity, productImage) {
    // Obtener el color y la talla seleccionados
    const selectedColor = document.getElementById('selected-color-name').innerText;
    const selectedSize = document.getElementById('talla').value;

    // Obtener el stoId basado en el color y la talla seleccionados
    if (!stockData[selectedColor] || !stockData[selectedColor][selectedSize]) {
        document.getElementById('errorModalBody').innerText = 'No se puede agregar al carrito. Stock insuficiente.';
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
        return;
    }
    const stoId = stockData[selectedColor][selectedSize].stoId;

    // Verificar el stock disponible
    const stockQuantity = stockData[selectedColor][selectedSize].cantidad;
    if (stockQuantity === 0 || parseInt(quantity) > stockQuantity) {
        // Mostrar el modal de error
        document.getElementById('errorModalBody').innerText = 'No se puede agregar al carrito. Stock insuficiente.';
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
        return;
    }
    // Crear un objeto del producto
    const product = {
        id: productId,
        name: productName,
        price: productPrice,
        quantity: parseInt(quantity), // Asegúrate de que la cantidad sea un número entero
        image: productImage,
        color: selectedColor,
        size: selectedSize,
        stoId: stoId // Añadir el stoId
    };

    // Obtener el carrito del localStorage o inicializarlo
    let cart = JSON.parse(localStorage.getItem('carrito')) || [];

    // Comprobar si el producto ya está en el carrito
    const existingProductIndex = cart.findIndex(item => item.id === productId && item.color === product.color && item.size === product.size);

    if (existingProductIndex > -1) {
        // Si el producto ya existe, actualizar la cantidad
        cart[existingProductIndex].quantity = parseInt(cart[existingProductIndex].quantity) + parseInt(quantity);
    } else {
        // Si no existe, añadir el nuevo producto
        cart.push(product);
    }

    // Guardar el carrito actualizado en el localStorage
    localStorage.setItem('carrito', JSON.stringify(cart));

    // Opcional: Mostrar un mensaje de éxito
    alert('Producto añadido al carrito');
}


</script>
<?php include "../scroll-marcas.php"; ?>
<?php include "../footer.php"; ?>