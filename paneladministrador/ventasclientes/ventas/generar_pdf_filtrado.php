<?php
require('../../fpdf/fpdf.php');
include '../../coneccionbd.php';
date_default_timezone_set('America/Lima'); // Ajusta la zona horaria


$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "
    SELECT v.venId, c.cliNombre, c.cliCorreo, c.cliDni, v.venFechaRegis, 
           SUM(dv.detVenPrecio * dv.detVenCantidad) AS total
    FROM ventas v
    INNER JOIN cliente c ON v.cliId = c.cliId
    INNER JOIN detalleventa dv ON v.venId = dv.venId
    WHERE c.cliNombre LIKE ?
";

$params = ["%$search%"];

if (!empty($fecha_desde) && !empty($fecha_hasta)) {
    $query .= " AND v.venFechaRegis BETWEEN ? AND ?";
    $params[] = $fecha_desde;
    $params[] = $fecha_hasta;
}

$query .= " GROUP BY v.venId ORDER BY v.venFechaRegis DESC";

$stmt = $con->prepare($query);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

class PDF extends FPDF
{
    function __construct()
    {
        parent::__construct(); 
    }
    function Header()
    {
        $this->Image('../../recursos/images/mens-store-dark.png', 150, 15, 46);
    
        $this->SetFont('Helvetica', '', 8);
        $this->Cell(300, 10, 'RUC: 2312344990765', 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, 'Telefono: 954455664', 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, utf8_decode('Direccion: Av. Cusco N° 321'), 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, utf8_decode('Email: menstore@hotmail.com'), 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, utf8_decode('Fecha y Hora: ' . date('Y-m-d H:i:s')), 0, 1, 'L', 0);
       
        $this->SetXY(80, 40);
        $this->SetFont('Helvetica', 'B', 20);
        $this->Cell(50, 8, 'REPORTE DE VENTAS', 0, 0, 'C', 0);
        $this->Ln(25);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetX(150);
        $this->SetFont('Helvetica', 'B', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function CreateTable($header, $data)
    {
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 220, 255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);

        $column_widths = [20, 40, 55, 20, 25, 30]; 

        $tableWidth = array_sum($column_widths);
        $startX = (210 - $tableWidth) / 2;
        $this->SetX($startX);

        // Encabezados de la tabla
        foreach ($header as $i => $col) {
            $this->Cell($column_widths[$i], 8, $col, 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 8);
        foreach ($data as $row) {
            $this->SetX($startX);
            $this->Cell($column_widths[0], 6, $row['venId'], 1, 0, 'C');
            $this->Cell($column_widths[1], 6, utf8_decode($row['cliNombre']), 1, 0, 'L');
            $this->Cell($column_widths[2], 6, $row['cliCorreo'], 1, 0, 'L');
            $this->Cell($column_widths[3], 6, $row['cliDni'], 1, 0, 'C');
            $this->Cell($column_widths[4], 6, number_format($row['total'], 2), 1, 0, 'R');
            $this->Cell($column_widths[5], 6, $row['venFechaRegis'], 1, 1, 'C');
        }
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$header = ['ID Venta', 'Cliente', 'Correo', 'DNI', 'Total', 'Fecha'];
$pdf->CreateTable($header, $result->fetch_all(MYSQLI_ASSOC));

$pdf->Output('I', 'Reporte_Ventas.pdf');
?>
