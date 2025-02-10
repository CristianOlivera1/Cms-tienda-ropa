<?php
include "../header.php";

$todo = mysqli_real_escape_string($con, $_GET["product_id"]);
$color_id = mysqli_real_escape_string($con, $_GET["color_id"]);

function getSizesByColor($con, $todo, $colorId) {
    return mysqli_query($con, "
        SELECT t.talNombre, p.proPrecio, oferta.ofePorcentaje 
        FROM stock s 
        INNER JOIN talla t ON s.talId = t.talId 
        INNER JOIN oferta ON oferta.stoId = s.stoId 
        INNER JOIN producto p ON p.proId = s.proId
        WHERE s.proId='$todo' AND s.colId='$colorId' AND oferta.ofeTiempo >= NOW()");
}

$sizes = getSizesByColor($con, $todo, $color_id);
$sizesArray = [];

while ($size = mysqli_fetch_assoc($sizes)) {
    $sizesArray[] = $size;
}

echo json_encode($sizesArray);
?>