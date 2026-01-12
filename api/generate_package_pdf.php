<?php
// Get data from query parameters
$packageName = $_GET['package_name'] ?? 'Untitled Package';
$clientName = $_GET['client_name'] ?? '';
$notes = $_GET['notes'] ?? '';
$finalTotal = $_GET['final_total'] ?? 0;
$currency = $_GET['currency'] ?? 'LKR ';
$eventHours = $_GET['event_hours'] ?? 0;

// Sanitize inputs
$packageName = htmlspecialchars($packageName);
$clientName = htmlspecialchars($clientName);
$currency = htmlspecialchars($currency);
$eventHours = floatval($eventHours);

// Generate quotation number
$quotationNumber = 'Q-' . date('Ymd') . strtoupper(substr(md5($packageName . time()), 0, 6));

// Calculate expiry date (1 week from now)
$expiryDate = date('F j, Y', strtotime('+1 week'));

// Set headers for HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $packageName; ?> - Package Details</title>
    <style>
        @page { size: A4; margin: 0; }
        @media print {
            body { margin: 0; padding: 30mm 20mm; }
            .no-print { display: none; }
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body { 
            margin: 0; 
            padding: 30mm 20mm; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #374151;
            background: #ffffff;
        }
        .header { 
            margin-bottom: 35px;
            padding-bottom: 0;
            border-bottom: none;
        }
        .title { 
            font-size: 36pt; 
            font-weight: 700; 
            color: #ea580c;
            margin-bottom: 2px;
            letter-spacing: -0.5px;
        }
        .subtitle { 
            font-size: 13pt; 
            color: #6b7280;
            font-weight: 400;
        }
        .section-title {
            font-size: 16pt;
            font-weight: 600;
            color: #dc2626;
            margin-top: 0;
            margin-bottom: 18px;
            padding-bottom: 0;
            border-bottom: none;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 40px;
            margin-bottom: 30px;
        }
        .detail-item {
            margin-bottom: 14px;
        }
        .detail-label {
            font-size: 10pt;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 400;
        }
        .detail-value {
            font-size: 11pt;
            color: #1f2937;
            font-weight: 500;
        }
        .package-box {
            background: linear-gradient(135deg, #fed7aa 0%, #fef3c7 100%);
            padding: 25px;
            margin: 30px 0;
            border-radius: 8px;
            border-left: 5px solid #f59e0b;
        }
        .package-title {
            font-size: 24pt;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 8px;
            line-height: 1.2;
        }
        .package-description {
            font-size: 10pt;
            color: #ea580c;
            font-style: italic;
        }
        .services-section {
            margin: 30px 0;
        }
        .services-title {
            font-size: 14pt;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .service-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-left: 5px;
        }
        .check-icon {
            color: #10b981;
            font-size: 16pt;
            margin-right: 12px;
            line-height: 1;
            flex-shrink: 0;
        }
        .service-content {
            flex: 1;
        }
        .service-name {
            font-size: 11pt;
            color: #1f2937;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .service-description {
            font-size: 9pt;
            color: #6b7280;
            line-height: 1.4;
        }
        .total-section {
            float: right;
            width: 320px;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .total-box {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 3px solid #10b981;
            text-align: right;
        }
        .total-label {
            font-size: 13pt;
            color: #374151;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .total-amount {
            font-size: 32pt;
            font-weight: 700;
            color: #10b981;
            line-height: 1;
        }
        .currency {
            font-size: 20pt;
            font-weight: 700;
            margin-right: 5px;
        }
        .note-box {
            clear: both;
            background: #fef3c7;
            padding: 15px 15px 15px 20px;
            margin: 30px 0;
            border-radius: 6px;
            border-left: 4px solid #f59e0b;
        }
        .note-icon {
            font-size: 12pt;
            margin-right: 8px;
        }
        .note-title {
            font-weight: 600;
            color: #92400e;
            font-size: 11pt;
            margin-bottom: 6px;
        }
        .note-content {
            color: #78350f;
            font-size: 10pt;
            line-height: 1.5;
        }
        .footer {
            margin-top: 60px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 9pt;
        }
        .footer p {
            margin: 4px 0;
        }
        .print-button {
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #dc2626, #f59e0b);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11pt;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
    <button class="print-button no-print" onclick="printPage()">� Print / Save as PDF</button>
    
    <!-- Header -->
    <div class="header">
        <div class="title">Price Calculator</div>
        <div class="subtitle">Hire Artist Studio</div>
    </div>
    
    <!-- Section Title -->
    <h1 class="section-title">Package Details</h1>
    
    <!-- Details Grid -->
    <div class="details-grid">
        <div>
            <?php if (!empty($clientName)): ?>
            <div class="detail-item">
                <div class="detail-label">Client Name:</div>
                <div class="detail-value"><?php echo $clientName; ?></div>
            </div>
            <?php endif; ?>
            <div class="detail-item">
                <div class="detail-label">Date:</div>
                <div class="detail-value"><?php echo date('F j, Y'); ?></div>
            </div>
        </div>
        <div>
            <div class="detail-item">
                <div class="detail-label">Quotation #:</div>
                <div class="detail-value"><?php echo $quotationNumber; ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Expires On:</div>
                <div class="detail-value"><?php echo $expiryDate; ?></div>
            </div>
        </div>
    </div>
    
    <!-- Package Box -->
    <div class="package-box">
        <div class="package-title"><?php echo $packageName; ?></div>
        <div class="package-description">A fantastic package to capture your special day.</div>
    </div>
    
    <!-- Important -->
    <div class="services-section">
        <div class="services-title">Important</div>
        
        <div class="service-item">
            <div class="check-icon">✓</div>
            <div class="service-content">
                <div class="service-name"><?php echo number_format($eventHours, 1); ?> Hours of Photography Coverage</div>
            </div>
        </div>
        
        <div class="service-item">
            <div class="check-icon">✓</div>
            <div class="service-content">
                <div class="service-name">High-Resolution Edited Images</div>
            </div>
        </div>
        
        <div class="service-item">
            <div class="check-icon">✓</div>
            <div class="service-content">
                <div class="service-name">Custom Photo Album Design</div>
                <div class="service-description">Optional printing services available at extra cost.</div>
            </div>
        </div>
    </div>
    
    <!-- Total Section -->
    <div class="total-section">
        <div class="total-box">
            <div class="total-label">Total Package Price</div>
            <div class="total-amount">
                <span class="currency"><?php echo trim($currency); ?></span><?php echo number_format($finalTotal, 2); ?>
            </div>
        </div>
    </div>
    
    <!-- Note -->
    <?php if (!empty($notes)): ?>
    <div class="note-box">
        <div class="note-title"><span class="note-icon">⚠</span> Important Note</div>
        <div class="note-content"><?php echo nl2br(htmlspecialchars($notes)); ?></div>
    </div>
    <?php else: ?>
    <div class="note-box">
        <div class="note-title"><span class="note-icon">⚠</span> Important Note</div>
        <div class="note-content">This price is only valid during week time bookings.</div>
    </div>
    <?php endif; ?>
    
    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Hire Artist Studio. All rights reserved.</p>
        <p>Thank you for choosing Price Calculator for your photography pricing needs.</p>
    </div>
</body>
</html>
