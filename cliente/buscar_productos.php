<?php
include "coneccionbd.php";

$searchTerm = mysqli_real_escape_string($con, $_GET['term']);

$query = "SELECT distinct p.proId, p.proNombre, p.proImg 
          FROM producto p 
          JOIN stock s ON p.proId = s.proId 
          INNER JOIN estado e ON e.estId = s.estId
          WHERE p.proNombre LIKE '%$searchTerm%' 
          AND s.stoCantidad > 0 
          AND e.estDisponible = 'Disponible'
          LIMIT 7";
$result = mysqli_query($con, $query);

$suggestions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $suggestions[] = $row;
}

echo json_encode($suggestions);
?>