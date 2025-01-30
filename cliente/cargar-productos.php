<?php
include "coneccionbd.php";

header('Content-Type: application/json');

$category = $_GET['category'];

if ($category == 'home') {
    $product_query = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre FROM producto p
                      INNER JOIN stock s ON p.proId = s.proId INNER JOIN marca m on m.marId=p.marId
                      WHERE s.stoCantidad > 0";
} else {
    $product_query = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre FROM producto p
                      INNER JOIN stock s ON p.proId = s.proId INNER JOIN marca m on m.marId=p.marId
                      WHERE s.stoCantidad > 0 AND p.catId = $category";
}

$product_query .= " ORDER BY p.proId DESC";
$product_result = mysqli_query($con, $product_query);

if (!$product_result) {
    echo json_encode(['error' => 'Error en la consulta de productos: ' . mysqli_error($con)]);
    exit;
}

$products = '';
while ($rod = mysqli_fetch_array($product_result)) {
    $id = "$rod[proId]";
    $name = "$rod[proNombre]";
    $price = "$rod[proPrecio]";
    $ufile = "$rod[proImg]";
    $marNombre = "$rod[marNombre]";

    $products .= "
    <div class='swiper-slide'>
        <a href='producto/detalleproducto.php?id=$id' class='hover-products'>
            <div class='single-service color-1 bg-hover hover-bottom text-center' style='padding:5px 15px 10px'>
                <img src='../paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                <p class='text-muted font-italic mt-2'>$marNombre</p>
                <h5 class='my-1'>$name</h5>
                <p>S/ $price</p>
            </div>
        </a>
    </div>
    ";
}

echo json_encode(['products' => $products]);
?>