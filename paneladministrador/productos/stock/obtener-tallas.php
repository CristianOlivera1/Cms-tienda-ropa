<?php
include "../../coneccionbd.php";

if (isset($_POST['proId'])) {
    $proId = intval($_POST['proId']); // Asegúrate de convertir a entero

    // Obtener la categoría del producto
    $query = "SELECT c.catNombre FROM producto p 
              INNER JOIN categoria c ON p.catId = c.catId 
              WHERE p.proId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $proId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $categoria = $result->fetch_assoc()['catNombre'];

        if (strtolower($categoria) === 'zapatillas' || strtolower($categoria) === 'zapatos') {
            // Filtrar solo tallas numéricas
            $query = "SELECT talId, talNombre FROM talla WHERE talNombre REGEXP '^[0-9]+$' order by talNombre asc";
        } else {
            $query = "SELECT talId, talNombre FROM talla WHERE talNombre NOT REGEXP '^[0-9]+$' order by talNombre asc";
        }
        
        $result = mysqli_query($con, $query);
        $tallas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $tallas[] = $row;
        }

        echo json_encode($tallas);
    } else {
        echo json_encode([]); // Producto no encontrado
    }
} else {
    echo json_encode([]); // No se envió proId
}
