<?php
// Ensure this is only called from the controller
if (!isset($reserva)) {
    exit('Acceso directo no permitido');
}

require_once 'LIB/FPDF/fpdf.php';

// Generate a random report number
$codigo_reporte = 'REP-' . mt_rand(100000, 999999);

// Helper para reemplazar utf8_decode (obsoleto en PHP 8.2)
function to_iso($str)
{
    return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
}   

class PDF extends FPDF
{
    public $codigo_reporte = '';

    // Cabecera de página
    function Header()
    {
        // Banner top
        $this->SetFillColor(0, 51, 102); // Dark blue
        $this->Rect(0, 0, 210, 30, 'F');

        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(255, 255, 255);
        $this->SetY(10);
        $this->SetX(10);
        $this->Cell(0, 10, to_iso('HOTEL VILLA MARINA'), 0, 0, 'L');

        // Report number on the right
        $this->SetFont('Arial', '', 12);
        $this->SetX(100);
        $this->Cell(100, 10, to_iso('REPORTE # ' . $this->codigo_reporte), 0, 1, 'R');
        $this->Ln(15);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 2 cm del final
        $this->SetY(-20);

        // Línea divisoria
        $this->SetDrawColor(0, 51, 102);
        $this->Line(10, $this->GetY(), 200, $this->GetY());

        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        // Número de página
        $this->Cell(0, 10, to_iso('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crear instancia del PDF
$pdf = new PDF();
$pdf->codigo_reporte = $codigo_reporte; // Pasamos el código al objeto
$pdf->AliasNbPages();
$pdf->AddPage();

// Company Info & Status
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(33, 37, 41);
$pdf->Cell(100, 6, to_iso('Emitido por:'), 0, 0, 'L');
$pdf->Cell(90, 6, to_iso('Fecha de Emisión:'), 0, 1, 'R');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 6, to_iso('Hotel Villa Marina'), 0, 0, 'L');
$pdf->Cell(90, 6, date('d/m/Y H:i'), 0, 1, 'R');

$pdf->Cell(100, 6, to_iso('Dirección: Zona Costera, Ciudad'), 0, 0, 'L');
$pdf->Cell(90, 6, to_iso('Estado de la Reserva:'), 0, 1, 'R');

$estados = [
    1 => 'Activa (Confirmada)',
    2 => 'Pendiente',
    3 => 'Completada',
    4 => 'Cancelada'
];
$estadoStr = $estados[$reserva['estado']] ?? 'Desconocido';

// Change color based on status
if ($reserva['estado'] == 1 || $reserva['estado'] == 3) {
    $pdf->SetTextColor(0, 128, 0); // Green
} elseif ($reserva['estado'] == 2) {
    $pdf->SetTextColor(204, 153, 0); // Yellow/Orange
} else {
    $pdf->SetTextColor(204, 0, 0); // Red
}

$pdf->Cell(100, 6, to_iso('Teléfono: +57 300 000 0000'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(90, 6, to_iso($estadoStr), 0, 1, 'R');

$pdf->Ln(10);

// Client Info (Invoice to)
$pdf->SetFillColor(240, 240, 240);
$pdf->SetDrawColor(200, 200, 200);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 51, 102); // Dark blue text
$pdf->Cell(0, 8, to_iso('  DATOS DEL CLIENTE'), 1, 1, 'L', true);

$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(50, 50, 50);

$u = $_SESSION['usuario'] ?? [];
$nombre_cliente = trim(($u['nombre'] ?? 'Huésped') . ' ' . ($u['apellido'] ?? ''));
$correo_cliente = $u['email'] ?? 'No registrado';
$documento_cliente = $u['documento'] ?? 'No registrado';
$telefono_cliente = $u['telefono'] ?? 'No registrado';

$tipo_doc = '';
if (isset($u['tipo_documento_id'])) {
    if ($u['tipo_documento_id'] == 1)
        $tipo_doc = 'Cédula de Ciudadanía';
    elseif ($u['tipo_documento_id'] == 2)
        $tipo_doc = 'Tarjeta de Identidad';
    elseif ($u['tipo_documento_id'] == 3)
        $tipo_doc = 'Cédula de Extranjería';
    elseif ($u['tipo_documento_id'] == 4)
        $tipo_doc = 'Pasaporte';
    else
        $tipo_doc = 'ID: ' . $u['tipo_documento_id'];
}

$pdf->Cell(0, 8, to_iso('  Nombre: ') . to_iso($nombre_cliente), 'LR', 1, 'L');
$pdf->Cell(0, 8, to_iso('  Tipo de Documento: ') . to_iso($tipo_doc), 'LR', 1, 'L');
$pdf->Cell(0, 8, to_iso('  Número de Documento: ') . to_iso($documento_cliente), 'LR', 1, 'L');
$pdf->Cell(0, 8, to_iso('  Teléfono: ') . to_iso($telefono_cliente), 'LR', 1, 'L');
$pdf->Cell(0, 8, to_iso('  Correo / Contacto: ') . to_iso($correo_cliente), 'LRB', 1, 'L');
$pdf->Ln(5);

// Reservation Details
$pdf->SetFillColor(0, 51, 102);
$pdf->SetDrawColor(0, 51, 102);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, to_iso('  DETALLES DE LA RESERVA'), 1, 1, 'L', true);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 11);

function renderRow($pdf, $label, $value, $fill = false)
{
    if ($fill) {
        $pdf->SetFillColor(248, 249, 250);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(70, 10, to_iso('  ' . $label), 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(120, 10, to_iso('  ' . $value), 1, 1, 'L', true);
}

renderRow($pdf, 'Categoría de Habitación', $reserva['tipo'], false);
renderRow($pdf, 'Número de Habitación', "Habitación " . $reserva['habitacion'], true);
renderRow($pdf, 'Fecha de Entrada', date('d/m/Y', strtotime($reserva['entrada'])), false);
renderRow($pdf, 'Fecha de Salida', date('d/m/Y', strtotime($reserva['salida'])), true);
renderRow($pdf, 'Total Noches', $reserva['noches'] . " noche(s)", false);
renderRow($pdf, 'Número de Personas', $reserva['personas'] . " persona(s)", true);
renderRow($pdf, 'Método de Pago Seleccionado', $reserva['pago'] ?? 'N/A', false);

$pdf->Ln(10);

// Totals
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(240, 240, 240);
$pdf->SetDrawColor(200, 200, 200);
$pdf->SetTextColor(50, 50, 50);

$pdf->Cell(110, 10, '', 0, 0, 'C');
$pdf->Cell(40, 10, to_iso('SUBTOTAL'), 1, 0, 'R', true);
$pdf->Cell(40, 10, '$' . number_format($reserva['total'], 0, ',', '.'), 1, 1, 'R');

$pdf->Cell(110, 10, '', 0, 0, 'C');
$pdf->SetFillColor(0, 51, 102); // Dark blue for total
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor(0, 51, 102);
$pdf->Cell(40, 10, to_iso('TOTAL'), 1, 0, 'R', true);
$pdf->Cell(40, 10, '$' . number_format($reserva['total'], 0, ',', '.'), 1, 1, 'R', true);

$pdf->Ln(25);

// Thanks Note
$pdf->SetTextColor(100, 100, 100);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 6, to_iso("Este documento certifica su reserva en Hotel Villa Marina. Por favor, presente este reporte al momento de su llegada.\nSi tiene alguna duda o necesita realizar un cambio en su estadía, contáctenos.\n\n¡Gracias por elegirnos!"), 0, 'C');

// Salida del PDF con nombre dinámico random
$pdf->SetTitle('Reporte de Reserva ' . $codigo_reporte);
$pdf->Output('I', $codigo_reporte . '.pdf');
?>