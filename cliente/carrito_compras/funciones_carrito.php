<?php
session_start();
include('../coneccionbd.php');
// Establecer las cabeceras para indicar que la respuesta es en formato JSON
header('Content-Type: application/json');

if (isset($_POST["aumentarCantidad"])) {
    $idProd = $_POST['proId'];
    $cantidaProducto = $_POST['aumentarCantidad'];
    

    if (!empty($tokenCliente)) {
        $UpdateCant = "UPDATE pedidostemporales 
                        SET cantidad ='$cantidaProducto' 
                        
                        AND producto_id='$idProd'";
        $result = mysqli_query($con, $UpdateCant);

        $responseData = array(
            'estado' => 'OK',
            'totalPagar' => totalAccionAumentarDisminuir($con, $tokenCliente)
        );
        echo json_encode($responseData);
    }
}

/**
 * Agregar a carrito de compra el producto
 */
if (isset($_POST["accion"]) && $_POST["accion"] == "addCar") {
    $_SESSION['tokenStoragel'] = $_POST['tokenCliente'];
    $idProduct = $_POST['proId'];
    $tokenCliente = $_POST['tokenCliente'] ?? '';

    if (!empty($tokenCliente)) {
        $ConsultarProduct = "SELECT * FROM pedidostemporales WHERE tokenCliente='$tokenCliente' AND producto_id='$idProduct'";
        $jqueryProduct = mysqli_query($con, $ConsultarProduct);

        if (mysqli_num_rows($jqueryProduct) > 0) {
            $DataProducto = mysqli_fetch_array($jqueryProduct);
            $newCantidad = ($DataProducto['cantidad'] + 1);

            $UdateCantidad = "UPDATE pedidostemporales SET cantidad='$newCantidad' WHERE producto_id='$idProduct' AND tokenCliente='$tokenCliente'";
            $resultUpdat = mysqli_query($con, $UdateCantidad);
        } else {
            $InsertProduct = "INSERT INTO pedidostemporales (producto_id, cantidad, tokenCliente) VALUES ('$idProduct','1','$tokenCliente')";
            $result = mysqli_query($con, $InsertProduct);
        }

        $SqlTotalProduct = "SELECT SUM(cantidad) AS totalProd FROM pedidostemporales WHERE tokenCliente='$tokenCliente' GROUP BY tokenCliente";
        $jqueryTotalProduct = mysqli_query($con, $SqlTotalProduct);
        $DataTotalProducto = mysqli_fetch_array($jqueryTotalProduct);
        echo $DataTotalProducto['totalProd'];
    }
}

/**
 * Disminuir cantidad de mi carrito de compra
 */
if (isset($_POST["accion"]) && $_POST["accion"] == "disminuirCantidad") {
    $_SESSION['tokenStoragel'] = $_POST['tokenCliente'];
    $idProd = mysqli_real_escape_string($con, $_POST['proId']);
    $tokenCliente = mysqli_real_escape_string($con, $_POST['tokenCliente']);
    $cantidad_Disminuida = mysqli_real_escape_string($con, $_POST['cantidad_Disminuida']);

    if (!empty($tokenCliente)) {
        if ($cantidad_Disminuida == 0) {
            $DeleteRegistro = "DELETE FROM pedidostemporales WHERE tokenCliente='$tokenCliente' AND producto_id='$idProd'";
            mysqli_query($con, $DeleteRegistro);
            $responseData = array(
                'totalProductos' => totalProductosSeleccionados($con, $tokenCliente),
                'totalPagar' => totalAccionAumentarDisminuir($con, $tokenCliente),
                'estado' => 'OK'
            );
        } else {
            $UpdateCant = "UPDATE pedidostemporales 
                           SET cantidad ='$cantidad_Disminuida' 
                           WHERE tokenCliente='$tokenCliente' 
                           AND producto_id='$idProd'";
            mysqli_query($con, $UpdateCant);

            $responseData = array(
                'totalProductos' => totalProductosSeleccionados($con, $tokenCliente),
                'totalPagar' => totalAccionAumentarDisminuir($con, $tokenCliente),
                'estado' => 'OK'
            );
        }

        echo json_encode($responseData);
    }
}

/**
 * Borrar producto del carrito
 */
if (isset($_POST["accion"]) && $_POST["accion"] == "borrarproductoModal") {
    $tokenCliente = $_POST['tokenCliente'] ?? '';

    if (!empty($tokenCliente)) {
        $DeleteRegistro = "DELETE FROM pedidostemporales WHERE producto_id= '" . $_POST["proId"] . "' ";
        mysqli_query($con, $DeleteRegistro);

        $respData = array(
            'totalProductos' => totalProductosSeleccionados($con, $tokenCliente),
            'totalProductoSeleccionados' => totalProductosBD($con, $tokenCliente),
            'totalPagar' => totalAccionAumentarDisminuir($con, $tokenCliente),
            'estado' => 'OK'
        );
        echo json_encode($respData);
    }
}

/**
 * Total productos en mi carrito de compra
 */
function totalProductosBD($con, $tokenCliente)
{
    if (!empty($tokenCliente)) {
        $sqlTotalProduct = "SELECT SUM(cantidad) AS totalProd FROM pedidostemporales WHERE tokenCliente='$tokenCliente' GROUP BY tokenCliente";
        $jqueryTotalProduct = mysqli_query($con, $sqlTotalProduct);
        if ($jqueryTotalProduct) {
            $dataTotalProducto = mysqli_fetch_array($jqueryTotalProduct);
            return $dataTotalProducto["totalProd"];
        }
    }
    return 0;
}

function totalAccionAumentarDisminuir($con, $tokenCliente)
{
    if (!empty($tokenCliente)) {
        $SqlDeudaTotal = "
            SELECT SUM(p.proPrecio * pt.cantidad) AS totalPagar 
            FROM productos AS p
            INNER JOIN pedidostemporales AS pt
            ON p.proId = pt.producto_id
            WHERE pt.tokenCliente = '$tokenCliente'";
        $jqueryDeuda = mysqli_query($con, $SqlDeudaTotal);
        $dataDeuda = mysqli_fetch_array($jqueryDeuda);
        return $dataDeuda['totalPagar'];
    }
    return 0;
}

/**
 * Funcion que verifica si hay pedidos activos por el usuario
 */
function totalProductosSeleccionados($con, $tokenCliente)
{
    if (!empty($tokenCliente)) {
        $ConsultarProduct = "SELECT * FROM pedidostemporales WHERE tokenCliente='$tokenCliente'";
        $jqueryProduct = mysqli_query($con, $ConsultarProduct);
        if (mysqli_num_rows($jqueryProduct) > 0) {
            return mysqli_num_rows($jqueryProduct);
        }
    }
    return 0;
}

/**
 * Limpiar carrito
 */
if (isset($_POST["accion"]) && $_POST["accion"] == "limpiarTodoElCarrito") {
    session_unset();
    session_destroy();
    echo json_encode(['mensaje' => 1]);
}
