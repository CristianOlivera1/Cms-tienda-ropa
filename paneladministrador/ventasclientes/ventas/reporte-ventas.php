<?php
require('../../fpdf/fpdf.php');
include ("../../coneccionbd.php");
date_default_timezone_set('America/Lima'); // Ajusta la zona horaria

// Realizar la consulta a la base de datos  
$query = "SELECT 
    v.venId,
    v.venFechaRegis,
    c.cliNombre, 
    CONCAT(c.cliApellidoPaterno, ' ', c.cliApellidoMaterno) AS Apellidos, 
    c.cliCorreo, 
    c.cliDni, 
    SUM(d.detVenCantidad * d.detVenPrecio) AS PrecioTotal
FROM ventas v
INNER JOIN cliente c ON v.cliId = c.cliId
INNER JOIN detalleventa d ON v.venId = d.venId
GROUP BY v.venId, c.cliNombre, c.cliApellidoPaterno, c.cliApellidoMaterno, c.cliCorreo, c.cliDni;
";
$result = $con->query($query); // Cambia mysqli_query por $con->query

$data = [];
$fechaVenta = ''; // Variable para almacenar la fecha de la venta
while ($row = $result->fetch_assoc()) { // Cambia mysqli_fetch_assoc por $result->fetch_assoc()
    $data[] = $row;
    $fechaVenta = $row['venFechaRegis']; // Almacena la fecha de la última venta
}

class PDF extends FPDF
{
    protected $fechaVenta; // Propiedad para almacenar la fecha de la venta

    // Constructor para recibir la fecha de la venta
    function __construct($fechaVenta)
    {
        parent::__construct();
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
        $this->Cell(300, 10, utf8_decode('Direccion: Av. Cusco N° 321'), 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, utf8_decode('Email: menstore@hotmail.com'), 0, 0, 'L', 0);
        $this->Ln(4);
        $this->Cell(300, 10, utf8_decode('Fecha y Hora: ' . date('Y-m-d H:i:s')), 0, 1, 'L', 0);
        $this->SetXY(80, 40); 
        $this->SetFont('Helvetica', 'B', 20);
        $this->Cell(50, 8, 'REPORTE DE VENTAS', 0, 0, 'C', 0);
        $this->SetXY(50,30);
        $this->Ln(25);
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetX(150);
        $this->SetFont('Helvetica', 'B', 8);
        $this->Cell(0, 10, utf8_decode('Página') . $this->PageNo() . '/{nb}', 0, 0, 'C');
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
$pdf = new PDF($fechaVenta); // Pasar la fecha al constructor
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetX(15);
$pdf->SetFont('Helvetica', 'B', 8);

// Set column widths
$pdf->SetWidths(array(10,  30, 30, 30, 40, 20, 20, 20));

// Header row (con fondo azul y texto blanco)
$pdf->SetFillColor(30, 144, 255); // Color de fondo azul
$pdf->SetTextColor(255, 255, 255); // Color de texto blanco

// Header row
$pdf->Cell(10, 8, 'N', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Cod. Venta', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Nombres', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Apellidos', 1, 0, 'C', 1);
$pdf->Cell(40, 8, 'Correo', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'DNI', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Total', 1, 0, 'C', 1);
$pdf->Ln();

// Restablecer colores a los valores predeterminados
$pdf->SetTextColor(0, 0, 0); // Texto negro
$pdf->SetFillColor(255, 255, 255); // Fondo blanco

// Fill data rows
$pdf->SetFont('Helvetica', '', 7);
for ($i = 0; $i < count($data); $i++) {
    $pdf->Row(array(
        $i + 1,
        utf8_decode($data[$i]['venId']),
        utf8_decode($data[$i]['cliNombre']),
        utf8_decode($data[$i]['Apellidos']),
        utf8_decode($data[$i]['cliCorreo']),
        utf8_decode($data[$i]['cliDni']),
        utf8_decode($data[$i]['PrecioTotal']),
    ), 15);
}

$pdf->Output();
?>