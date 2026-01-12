<?php
// Get data from query parameters
$packageName = $_GET['package_name'] ?? 'Untitled Package';
$clientName = $_GET['client_name'] ?? '';
$laborHours = $_GET['labor_hours'] ?? 0;
$hourlyRate = $_GET['hourly_rate'] ?? 0;
$marginPercentage = $_GET['margin_percentage'] ?? 0;
$equipmentTotal = $_GET['equipment_total'] ?? 0;
$laborTotal = $_GET['labor_total'] ?? 0;
$subtotal = $_GET['subtotal'] ?? 0;
$marginAmount = $_GET['margin_amount'] ?? 0;
$finalTotal = $_GET['final_total'] ?? 0;
$notes = $_GET['notes'] ?? '';
$equipment = json_decode($_GET['equipment'] ?? '[]', true);
$currency = $_GET['currency'] ?? 'LKR ';

// Sanitize currency
$currency = htmlspecialchars($currency);

// Set headers for HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($packageName); ?> - Quotation</title>
    <style>
        @page { size: A4; margin: 0; }
        @media print {
            body { margin: 0; padding: 20mm; }
            .no-print { display: none; }
        }
        body { 
            margin: 0; 
            padding: 20mm; 
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #1f2937;
        }
        .header { 
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #f59e0b;
        }
        .title { 
            font-size: 28pt; 
            font-weight: bold; 
            color: #ea580c;
            margin-bottom: 5px;
        }
        .subtitle { 
            font-size: 14pt; 
            color: #6b7280;
            font-weight: 300;
            letter-spacing: 1px;
        }
        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #dc2626;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-box {
            background-color: #f9fafb;
            padding: 15px;
            border-left: 4px solid #f59e0b;
            margin: 15px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
        }
        .info-value {
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background-color: #2563eb;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-table {
            margin-top: 30px;
            width: 60%;
            margin-left: auto;
        }
        .summary-table td {
            padding: 8px 12px;
        }
        .summary-row {
            font-weight: 500;
        }
        .subtotal-row {
            border-top: 2px solid #9ca3af;
            font-weight: 600;
        }
        .total-row {
            background-color: #dbeafe;
            font-weight: bold;
            font-size: 13pt;
            border-top: 3px solid #2563eb;
        }
        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10pt;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #dc2626, #f59e0b);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14pt;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .print-button:hover {
            background: linear-gradient(135deg, #b91c1c, #d97706);
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <button class="print-button no-print" onclick="printPage()">🖨️ Print / Save as PDF</button>
    
    <div class="header">
        <div class="title">Price Calculator</div>
        <div class="subtitle">Hire Artist Studio</div>
    </div>
    
    <h1 class="section-title">Package Quotation</h1>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Package Name:</span>
            <span class="info-value"><?php echo htmlspecialchars($packageName); ?></span>
        </div>
        <?php if (!empty($clientName)): ?>
        <div class="info-row">
            <span class="info-label">Client:</span>
            <span class="info-value"><?php echo htmlspecialchars($clientName); ?></span>
        </div>
        <?php endif; ?>
        <div class="info-row">
            <span class="info-label">Date:</span>
            <span class="info-value"><?php echo date('F j, Y'); ?></span>
        </div>
    </div>

    <?php if (!empty($equipment) && is_array($equipment)): ?>
    <h2 class="section-title">Equipment List</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Equipment</th>
                <th style="width: 20%;">Type</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 15%;">Unit Price</th>
                <th class="text-right" style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($equipment as $item): ?>
            <tr>
                <td>
                    <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                    <small style="color: #6b7280;"><?php echo htmlspecialchars($item['model']); ?></small>
                </td>
                <td><?php echo htmlspecialchars($item['type']); ?></td>
                <td class="text-center"><?php echo intval($item['quantity']); ?></td>
                <td class="text-right"><?php echo $currency . number_format($item['unit_value'], 2); ?></td>
                <td class="text-right"><?php echo $currency . number_format($item['total_value'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <h2 class="section-title">Cost Summary</h2>
    <table class="summary-table">
        <tbody>
            <tr class="summary-row">
                <td>Equipment Total:</td>
                <td class="text-right"><?php echo $currency . number_format($equipmentTotal, 2); ?></td>
            </tr>
            <tr class="summary-row">
                <td>Event Hours (<?php echo number_format($laborHours, 1); ?> hours @ <?php echo $currency . number_format($hourlyRate, 2); ?>/hr):</td>
                <td class="text-right"><?php echo $currency . number_format($laborTotal, 2); ?></td>
            </tr>
            <tr class="subtotal-row">
                <td>Subtotal:</td>
                <td class="text-right"><?php echo $currency . number_format($subtotal, 2); ?></td>
            </tr>
            <tr class="summary-row">
                <td>Profit Margin (<?php echo number_format($marginPercentage, 0); ?>%):</td>
                <td class="text-right"><?php echo $currency . number_format($marginAmount, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td><strong>FINAL TOTAL:</strong></td>
                <td class="text-right"><strong><?php echo $currency . number_format($finalTotal, 2); ?></strong></td>
            </tr>
        </tbody>
    </table>

    <?php if (!empty($notes)): ?>
    <div class="notes-section">
        <strong style="color: #92400e;">Notes:</strong><br>
        <?php echo nl2br(htmlspecialchars($notes)); ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Hire Artist Studio. All rights reserved.</p>
        <p>Thank you for choosing Price Calculator for your photography pricing needs.</p>
    </div>
</body>
</html>
<?php
// End of file - remove all code below
exit;

// OLD CODE BELOW - NOT USED
class SimplePDF {
    private $pageWidth = 210; // A4 width in mm
    private $pageHeight = 297; // A4 height in mm
    private $margin = 20;
    private $y = 20;
    private $content = [];
    
    public function addTitle($text) {
        $this->content[] = [
            'type' => 'title',
            'text' => $text,
            'y' => $this->y
        ];
        $this->y += 15;
    }
    
    public function addSubtitle($text) {
        $this->content[] = [
            'type' => 'subtitle',
            'text' => $text,
            'y' => $this->y
        ];
        $this->y += 10;
    }
    
    public function addText($text) {
        $this->content[] = [
            'type' => 'text',
            'text' => $text,
            'y' => $this->y
        ];
        $this->y += 7;
    }
    
    public function addLine() {
        $this->content[] = [
            'type' => 'line',
            'y' => $this->y
        ];
        $this->y += 5;
    }
    
    public function addSpace($height = 5) {
        $this->y += $height;
    }
    
    public function output() {
        // Generate HTML that looks like a PDF
        // In production, use a proper PDF library
        echo $this->generateHTML();
    }
    
    private function generateHTML() {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDF Document</title>
    <style>
        @page { size: A4; margin: 0; }
        body { 
            margin: 0; 
            padding: 20mm; 
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .title { 
            font-size: 24pt; 
            font-weight: bold; 
            color: #1e40af;
            margin-bottom: 10px;
        }
        .subtitle { 
            font-size: 14pt; 
            color: #666;
            margin-bottom: 5px;
        }
        .text { 
            font-size: 12pt; 
            margin-bottom: 3px;
        }
        .line { 
            border-bottom: 2px solid #e5e7eb; 
            margin: 10px 0;
        }
        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1e40af;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            font-size: 14pt;
            background-color: #dbeafe;
        }
        .info-box {
            background-color: #f9fafb;
            padding: 10px;
            border-left: 4px solid #2563eb;
            margin: 10px 0;
        }
        @media print {
            body { margin: 0; padding: 20mm; }
        }
    </style>
    <script>
        window.onload = function() { window.print(); }
    </script>
</head>
<body>';
        
        foreach ($this->content as $item) {
            switch ($item['type']) {
                case 'title':
                    $html .= '<div class="title">' . htmlspecialchars($item['text']) . '</div>';
                    break;
                case 'subtitle':
                    $html .= '<div class="subtitle">' . htmlspecialchars($item['text']) . '</div>';
                    break;
                case 'text':
                    $html .= '<div class="text">' . htmlspecialchars($item['text']) . '</div>';
                    break;
                case 'line':
                    $html .= '<div class="line"></div>';
                    break;
            }
        }
        
        $html .= '</body></html>';
        return $html;
    }
}

// Create PDF
$pdf = new SimplePDF();

// Header
$pdf->addTitle('Lens Calculator');
$pdf->addSubtitle('Hire Artist Studio');
$pdf->addLine();
$pdf->addSpace(5);

// Package Information
$pdf->addTitle('Package Quotation');
$pdf->addText('Package Name: ' . $packageName);
if (!empty($clientName)) {
    $pdf->addText('Client: ' . $clientName);
}
$pdf->addText('Date: ' . date('F j, Y'));
$pdf->addLine();
$pdf->addSpace(10);

// Equipment Section
$pdf->addSubtitle('Equipment List');
$pdf->addSpace(5);

// Create equipment table HTML
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . htmlspecialchars($packageName) . '</title>
    <style>
        @page { size: A4; margin: 0; }
        body { 
            margin: 0; 
            padding: 20mm; 
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #1f2937;
        }
        .header { 
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        .title { 
            font-size: 28pt; 
            font-weight: bold; 
            color: #1e40af;
            margin-bottom: 5px;
        }
        .subtitle { 
            font-size: 14pt; 
            color: #6b7280;
            font-weight: 300;
            letter-spacing: 1px;
        }
        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1e40af;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-box {
            background-color: #f9fafb;
            padding: 15px;
            border-left: 4px solid #2563eb;
            margin: 15px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
        }
        .info-value {
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background-color: #2563eb;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-table {
            margin-top: 30px;
            width: 60%;
            margin-left: auto;
        }
        .summary-table td {
            padding: 8px 12px;
        }
        .summary-row {
            font-weight: 500;
        }
        .subtotal-row {
            border-top: 2px solid #9ca3af;
            font-weight: 600;
        }
        .total-row {
            background-color: #dbeafe;
            font-weight: bold;
            font-size: 13pt;
            border-top: 3px solid #2563eb;
        }
        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10pt;
        }
        @media print {
            body { margin: 0; padding: 20mm; }
            tr { page-break-inside: avoid; }
        }
    </style>
    <script>
        window.onload = function() { 
            window.print(); 
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="title">Lens Calculator</div>
        <div class="subtitle">Hire Artist Studio</div>
    </div>
    
    <h1 class="section-title">Package Quotation</h1>
    
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Package Name:</span>
            <span class="info-value">' . htmlspecialchars($packageName) . '</span>
        </div>';

if (!empty($clientName)) {
    echo '<div class="info-row">
            <span class="info-label">Client:</span>
            <span class="info-value">' . htmlspecialchars($clientName) . '</span>
        </div>';
}

echo '<div class="info-row">
            <span class="info-label">Date:</span>
            <span class="info-value">' . date('F j, Y') . '</span>
        </div>
    </div>';

if (!empty($equipment)) {
    echo '<h2 class="section-title">Equipment List</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Equipment</th>
                <th style="width: 20%;">Type</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 15%;">Unit Price</th>
                <th class="text-right" style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($equipment as $item) {
        echo '<tr>
                <td><strong>' . htmlspecialchars($item['name']) . '</strong><br>
                    <small style="color: #6b7280;">' . htmlspecialchars($item['model']) . '</small>
                </td>
                <td>' . htmlspecialchars($item['type']) . '</td>
                <td class="text-center">' . intval($item['quantity']) . '</td>
                <td class="text-right">$' . number_format($item['unit_value'], 2) . '</td>
                <td class="text-right">$' . number_format($item['total_value'], 2) . '</td>
            </tr>';
    }
    
    echo '</tbody>
    </table>';
}

echo '<h2 class="section-title">Labor & Cost Summary</h2>
    <table class="summary-table">
        <tbody>
            <tr class="summary-row">
                <td>Equipment Total:</td>
                <td class="text-right">$' . number_format($equipmentTotal, 2) . '</td>
            </tr>
            <tr class="summary-row">
                <td>Labor (' . number_format($laborHours, 1) . ' hours @ $' . number_format($hourlyRate, 2) . '/hr):</td>
                <td class="text-right">$' . number_format($laborTotal, 2) . '</td>
            </tr>
            <tr class="subtotal-row">
                <td>Subtotal:</td>
                <td class="text-right">$' . number_format($subtotal, 2) . '</td>
            </tr>
            <tr class="summary-row">
                <td>Profit Margin (' . number_format($marginPercentage, 0) . '%):</td>
                <td class="text-right">$' . number_format($marginAmount, 2) . '</td>
            </tr>
            <tr class="total-row">
                <td><strong>FINAL TOTAL:</strong></td>
                <td class="text-right"><strong>$' . number_format($finalTotal, 2) . '</strong></td>
            </tr>
        </tbody>
    </table>';

if (!empty($notes)) {
    echo '<div class="notes-section">
            <strong style="color: #92400e;">Notes:</strong><br>
            ' . nl2br(htmlspecialchars($notes)) . '
        </div>';
}

echo '<div class="footer">
        <p>&copy; ' . date('Y') . ' Hire Artist Studio. All rights reserved.</p>
        <p>Thank you for choosing Lens Calculator for your photography pricing needs.</p>
    </div>
</body>
</html>';
?>
