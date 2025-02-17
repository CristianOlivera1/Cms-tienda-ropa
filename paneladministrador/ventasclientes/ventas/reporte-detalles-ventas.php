<?php
require('../../fpdf/fpdf.php');
include ("../../coneccionbd.php");
date_default_timezone_set('America/Lima'); 
// Obtener el venId de la URL
$venId = $_GET['venId'] ?? '';
if (empty($venId)) {
    die("Error: venId no se ha proporcionado.");
}

// Consulta para obtener los detalles de la venta
$query = "SELECT 
    p.proNombre, 
    t.talNombre, 
    c.colNombre, 
    d.detVenCantidad, 
    d.detVenPrecio, 
    (d.detVenCantidad * d.detVenPrecio) AS totalPrecio
FROM detalleventa d 
INNER JOIN stock s ON s.stoId = d.stoId 
INNER JOIN talla t ON s.talId = t.talId 
INNER JOIN color c ON c.colId = s.colId 
INNER JOIN producto p ON p.proId = s.proId
WHERE d.venId = '$venId';";

$result = $con->query($query);
$productos = $result->fetch_all(MYSQLI_ASSOC);
$data = [];
$fechaVenta = ''; // Variable para almacenar la fecha de la venta
while ($row = $result->fetch_assoc()) { // Cambia mysqli_fetch_assoc por $result->fetch_assoc()
    $data[] = $row;
    $fechaVenta = $row['venFechaRegis']; // Almacena la fecha de la última venta
}

// Calcular el total de la venta
$totalVenta = 0;
foreach ($productos as $producto) {
    $totalVenta += $producto['totalPrecio'];
}
// Clase PDF
class PDF extends FPDF
{

    protected $fechaVenta; // Propiedad para almacenar la fecha de la venta

    // Constructor para recibir la fecha de la venta
    function __construct($fechaVenta)
    {
        parent::__construct(); // Llama al constructor de la clase padre
        $this->fechaVenta = $fechaVenta; // Asigna la fecha a la propiedad
    }
    // Header
    function Header()
    {
        $this->Image('../../recursos/images/mens-store-dark.png', 150, 15, 46);
        $this->SetFont('Helvetica', '', 8);
        $this->Cell(300, 10, 'RUC: 2312344990765', 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, 'Telefono: 954455664', 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, iconv('UTF-8', 'ISO-8859-1', 'Direccion: Av. Cusco N° 321'), 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, iconv('UTF-8', 'ISO-8859-1', 'Email: menstore@hotmail.com'), 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, iconv('UTF-8', 'ISO-8859-1', 'Fecha y Hora: ' . date('Y-m-d H:i:s')), 0, 1, 'L', 0);
        $this->SetXY(80, 40); 
        $this->SetFont('Helvetica', 'B', 20);
        $this->Cell(50, 8, 'Detalles de la Venta', 0, 0, 'C', 0);
        $this->SetXY(50,30);
        $this->Ln(25);
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetX(150);
        $this->SetFont('Helvetica', 'B', 8);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Página') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    protected $widths;
    protected $aligns;

    function SetWidths($w)
    {
        $this->widths = $w;
    }

    function Row($data, $setX)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        $this->CheckPageBreak($h, $setX);
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    function CheckPageBreak($h, $setX)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
        $this->SetX($setX);
    }

    function NbLines($w, $txt)
    {
        if (!isset($this->CurrentFont))
            $this->Error('No font has been set');
        $cw = $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

// Instantiation of inherited class
$pdf = new PDF($fechaVenta);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetX(15);
$pdf->SetFont('Helvetica', 'B', 8);

// Set column widths
$pdf->SetWidths(array(10, 40, 15, 30, 20, 30, 20));

// Header row (con fondo azul y texto blanco)
$pdf->SetFillColor(30, 144, 255); // Color de fondo azul
$pdf->SetTextColor(255, 255, 255); // Color de texto blanco

// Header row
$pdf->Cell(10, 8, 'N', 1, 0, 'C', 1);
$pdf->Cell(40, 8, 'Producto', 1, 0, 'C', 1);
$pdf->Cell(15, 8, 'Talla', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Color', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Precio Unitario', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Total', 1, 1, 'C', 1);

// Reset text color
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Helvetica', '', 7);
// Data rows
$contador = 1;
foreach ($productos as $producto) {
    $pdf->Row(array(
        $contador++, // Número de fila
        iconv('UTF-8', 'ISO-8859-1', $producto['proNombre']), // Nombre del producto
        iconv('UTF-8', 'ISO-8859-1', $producto['talNombre']), // Talla
        iconv('UTF-8', 'ISO-8859-1', $producto['colNombre']), // Color
        $producto['detVenCantidad'], // Cantidad
        number_format($producto['detVenPrecio'], 2), // Precio Unitario
        number_format($producto['totalPrecio'], 2) // Total
    ), 15);
}
// Agregar el total de la venta
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->SetX(15);
$pdf->Cell(145, 10, 'Total de la Venta:', 0, 0, 'R', 0);
$pdf->Cell(20, 10, number_format($totalVenta, 2), 0, 0, 'L', 0);
// Output the PDF
$pdf->Output();
?>