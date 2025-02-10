<?php
include "../coneccionbd.php";

header('Content-Type: application/json');

$category = $_GET['category'];
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';

if ($category == 'home') {
    $offer_query = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre, o.ofePorcentaje 
                    FROM producto p 
                    INNER JOIN stock s ON p.proId = s.proId 
                    INNER JOIN oferta o ON s.stoId = o.stoId 
                    INNER JOIN marca m ON m.marId = p.marId 
                    INNER JOIN estado e ON e.estId = s.estId
                    WHERE s.stoCantidad > 0 AND o.ofeTiempo > NOW() AND e.estDisponible = 'Disponible'";
} else {
    $offer_query = "SELECT DISTINCT p.proId, p.proNombre, p.proPrecio, p.proImg, m.marNombre, o.ofePorcentaje 
                    FROM producto p 
                    INNER JOIN stock s ON p.proId = s.proId 
                    INNER JOIN oferta o ON s.stoId = o.stoId 
                    INNER JOIN marca m ON m.marId = p.marId 
                    INNER JOIN estado e ON e.estId = s.estId
                    WHERE s.stoCantidad > 0 AND p.catId = $category AND o.ofeTiempo > NOW() AND e.estDisponible = 'Disponible'";
}

if ($brand != '') {
    $offer_query .= " AND m.marNombre = '$brand'";
}

$offer_query .= " ORDER BY p.proId DESC";
$offer_result = mysqli_query($con, $offer_query);

if (!$offer_result) {
    echo json_encode(['error' => 'Error en la consulta de ofertas: ' . mysqli_error($con)]);
    exit;
}

$offers = '';
while ($rod = mysqli_fetch_array($offer_result)) {
    $id = $rod['proId'];
    $name = $rod['proNombre'];
    $price = $rod['proPrecio'];
    $ufile = $rod['proImg'];
    $marNombre = $rod['marNombre'];
    $discount = $rod['ofePorcentaje'];
    $finalPrice = $price - ($price * $discount / 100); // Calcula el precio final después del descuento

    $offers .= "
    <div class='swiper-slide'>
        <a href='detalleofertaproducto.php?id=$id' class='hover-products'>
            <div class='single-service color-1 bg-hover hover-bottom text-center' style='padding:5px 15px 15px'>
                <img src='/paneladministrador/recursos/uploads/producto/$ufile' alt='img' class='category-img'>
                <p class='text-muted font-italic mt-2'>$marNombre</p>
                <h5 class='my-1'>$name</h5>
                <p class='text-danger'>S/. $finalPrice <small class='text-muted'><del>S/. $price</del> ($discount% OFF)</small></p>
            </div>
        </a>
    </div>
    ";
}

echo json_encode(['offers' => $offers]);
?>