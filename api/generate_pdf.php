<?php
// Get data from query parameters
$packageName = $_GET['package_name'] ?? 'Untitled Package';
$clientName = $_GET['client_name'] ?? '';
$laborHours = $_GET['labor_hours'] ?? 0;
$hourlyRate = $_GET['hourly_rate'] ?? 0;
$marginPercentage = $_GET['margin_percentage'] ?? 0;
$equipmentTotal = $_GET['equipment_total'] ?? 0;
$laborTotal = $_GET['labor_total'] ?? 0;
$extraGearCost = floatval($_GET['extra_gear_cost'] ?? 0);
$transportationCost = floatval($_GET['transportation_cost'] ?? 0);
$assistantPay = floatval($_GET['assistant_pay'] ?? 0);
$editingCost = floatval($_GET['editing_cost'] ?? 0);
$additionalCost = floatval($_GET['additional_cost'] ?? 0);
$additionalCostsTotal = floatval($_GET['additional_costs_total'] ?? 0);
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
            margin-bottom: 8px;
        }
        .info-label {
            width: 160px;
            font-weight: 600;
            color: #374151;
        }
        .info-value {
            flex: 1;
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
            <?php if ($extraGearCost > 0): ?>
            <tr class="summary-row">
                <td>Extra Gear Cost:</td>
                <td class="text-right"><?php echo $currency . number_format($extraGearCost, 2); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($transportationCost > 0): ?>
            <tr class="summary-row">
                <td>Transportation Cost:</td>
                <td class="text-right"><?php echo $currency . number_format($transportationCost, 2); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($assistantPay > 0): ?>
            <tr class="summary-row">
                <td>Assistant Pay:</td>
                <td class="text-right"><?php echo $currency . number_format($assistantPay, 2); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($editingCost > 0): ?>
            <tr class="summary-row">
                <td>Editing / Retouching Cost:</td>
                <td class="text-right"><?php echo $currency . number_format($editingCost, 2); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($additionalCost > 0): ?>
            <tr class="summary-row">
                <td>Additional Cost:</td>
                <td class="text-right"><?php echo $currency . number_format($additionalCost, 2); ?></td>
            </tr>
            <?php endif; ?>
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
