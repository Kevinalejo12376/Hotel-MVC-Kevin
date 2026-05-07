<?php
// Ensure this is only called from the controller
if (!isset($reservas)) {
    exit('Acceso directo no permitido');
}

// Configurar zona horaria de Colombia
date_default_timezone_set('America/Bogota');

// Load Composer's autoloader for PhpSpreadsheet
$autoloadPath1 = __DIR__ . '/../LIB/SPREADSHEET/vendor/autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} elseif (file_exists($autoloadPath2)) {
    require_once $autoloadPath2;
} else {
    exit('Error: No se encontro el autoloader de Composer para PhpSpreadsheet. Asegurate de ejecutar "composer install" para poder generar reportes en Excel.');
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Mis Reservas');

// Set document properties
$spreadsheet->getProperties()->setCreator('Hotel Villa Marina')
    ->setLastModifiedBy('Hotel Villa Marina')
    ->setTitle('Reporte General de Reservas')
    ->setSubject('Reservas')
    ->setDescription('Reporte general de todas las reservas del usuario.')
    ->setCategory('Reporte');

// Add Header
$sheet->setCellValue('A1', 'HOTEL VILLA MARINA');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new Color(Color::COLOR_WHITE));
$sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00003366'); // Dark blue
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getRowDimension('1')->setRowHeight(30);

$sheet->setCellValue('A2', 'Reporte General de Reservas');
$sheet->mergeCells('A2:G2');
$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// User info
$u = $_SESSION['usuario'] ?? [];
$nombre_cliente = trim(($u['nombre'] ?? 'Huesped') . ' ' . ($u['apellido'] ?? ''));
$correo_cliente = $u['email'] ?? 'No registrado';
$documento_cliente = $u['documento'] ?? 'No registrado';

$sheet->setCellValue('A4', 'Cliente:');
$sheet->setCellValue('B4', $nombre_cliente);
$sheet->setCellValue('A5', 'Documento:');
$sheet->setCellValue('B5', $documento_cliente);
$sheet->setCellValue('A6', 'Correo:');
$sheet->setCellValue('B6', $correo_cliente);
$sheet->setCellValue('F4', 'Fecha de Reporte:');
$sheet->setCellValue('G4', date('d/m/Y H:i'));

$sheet->getStyle('A4:A6')->getFont()->setBold(true);
$sheet->getStyle('F4')->getFont()->setBold(true);

// Table headers
$headers = ['ID Reserva', 'Habitacion', 'Tipo', 'Entrada', 'Salida', 'Estado', 'Total ($)'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '8', $header);
    $col++;
}

// Style table headers
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => Color::COLOR_WHITE],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => '00003366'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FFCCCCCC'],
        ],
    ],
];
$sheet->getStyle('A8:G8')->applyFromArray($headerStyle);
$sheet->getRowDimension('8')->setRowHeight(20);

// Populate data
$row = 9;
$estados = [
    1 => 'Activa (Confirmada)',
    2 => 'Pendiente',
    3 => 'Completada',
    4 => 'Cancelada'
];

$totalIngresos = 0;

foreach ($reservas as $reserva) {
    $estadoStr = $estados[$reserva['estado']] ?? 'Desconocido';
    
    $sheet->setCellValue('A' . $row, $reserva['id']);
    $sheet->setCellValue('B' . $row, $reserva['habitacion']);
    $sheet->setCellValue('C' . $row, $reserva['tipo']);
    $sheet->setCellValue('D' . $row, date('d/m/Y', strtotime($reserva['entrada'])));
    $sheet->setCellValue('E' . $row, date('d/m/Y', strtotime($reserva['salida'])));
    $sheet->setCellValue('F' . $row, $estadoStr);
    $sheet->setCellValue('G' . $row, $reserva['total']);
    
    // Format currency
    $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('"$"#,##0_-');
    
    // Status color
    if ($reserva['estado'] == 1 || $reserva['estado'] == 3) {
        $sheet->getStyle('F' . $row)->getFont()->getColor()->setARGB('FF008000'); // Green
    } elseif ($reserva['estado'] == 2) {
        $sheet->getStyle('F' . $row)->getFont()->getColor()->setARGB('FFCC9900'); // Yellow
    } else {
        $sheet->getStyle('F' . $row)->getFont()->getColor()->setARGB('FFCC0000'); // Red
    }
    
    $totalIngresos += $reserva['total'];
    $row++;
}

// Add total row
$sheet->setCellValue('F' . $row, 'TOTAL:');
$sheet->setCellValue('G' . $row, $totalIngresos);
$sheet->getStyle('F' . $row . ':G' . $row)->getFont()->setBold(true);
$sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('"$"#,##0_-');
$sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

// Borders for data
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FFEEEEEE'],
        ],
    ],
    'alignment' => [
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
if ($row > 9) {
    $sheet->getStyle('A9:G' . ($row - 1))->applyFromArray($dataStyle);
}

// Auto size columns
foreach (range('A', 'G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Ensure clean output buffer before sending the Excel file
if (ob_get_length()) ob_end_clean();

// Generate a random report number
$codigo_reporte = 'EXCEL-REP-' . mt_rand(100000, 999999);

// Redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $codigo_reporte . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
