<?php
include "../../coneccionbd.php";  // Incluye la conexión a la base de datos

// Verifica si se ha pasado un ID de producto
if (isset($_GET['proId'])) {
    $proId = (int)$_GET['proId'];

    // Consulta para obtener los stocks del producto seleccionado, incluyendo talla y color
    $query_stock = "
        SELECT 
            stock.stoId, 
            producto.proNombre,
            talla.talNombre AS talla, 
            color.colNombre AS color
        FROM stock
        INNER JOIN producto ON producto.proId = stock.proId
        INNER JOIN talla ON talla.talId = stock.talId
        INNER JOIN color ON color.colId = stock.colId
        WHERE producto.proId = ?
    ";

    // Preparamos y ejecutamos la consulta
    if ($stmt = $con->prepare($query_stock)) {
        $stmt->bind_param('i', $proId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Comprobar si hay resultados
        if ($result->num_rows > 0) {
            $options = "<option value=''>Selecciona un stock</option>";
            while ($row = $result->fetch_assoc()) {
                // Concatenar el nombre del producto con la talla y el color
                $stockName = $row['proNombre'] . " - Talla: " . $row['talla'] . " - Color: " . $row['color'];
                $options .= "<option value='{$row['stoId']}'>{$stockName}</option>";
            }

            // Devolver las opciones en formato JSON
            echo json_encode(["options" => $options]);
        } else {
            echo json_encode(["options" => "<option value=''>No hay stocks disponibles</option>"]);
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo json_encode(["options" => "<option value=''>Error al consultar los stocks</option>"]);
    }
} else {
    echo json_encode(["options" => "<option value=''>Selecciona un producto primero</option>"]);
}
?>
