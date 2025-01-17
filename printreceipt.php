<?php
require_once __DIR__ . '/vendor/autoload.php'; // โหลด mPDF

// สร้างข้อมูลตัวอย่าง
$data = [
    'title' => 'Transaction Report',
    'date' => date('Y-m-d H:i:s'),
    'customer_name' => 'John Doe',
    'transaction_id' => 'TRX123456',
    'total_price' => 1500.50,
    'details' => [
        ['item' => 'Product A', 'qty' => 2, 'price' => 500],
        ['item' => 'Product B', 'qty' => 1, 'price' => 500.50],
    ]
];

// สร้าง HTML สำหรับ Report
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
        }
        .container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .details {
            margin: 10px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .details p {
            margin: 5px 0;
            line-height: 1.5;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>' . $data['title'] . '</h1>
            <p>Date: ' . $data['date'] . '</p>
        </div>
        <div class="details">
            <p><strong>Customer Name:</strong> ' . $data['customer_name'] . '</p>
            <p><strong>Transaction ID:</strong> ' . $data['transaction_id'] . '</p>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>';
foreach ($data['details'] as $detail) {
    $html .= '
                <tr>
                    <td>' . $detail['item'] . '</td>
                    <td>' . $detail['qty'] . '</td>
                    <td>' . number_format($detail['price'], 2) . '</td>
                </tr>';
}
$html .= '
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th>' . number_format($data['total_price'], 2) . '</th>
                </tr>
            </tfoot>
        </table>
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>';

// สร้างไฟล์ PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('ReportSlip.pdf', 'I'); // 'I' สำหรับแสดงผลบนเบราว์เซอร์, 'D' สำหรับดาวน์โหลด
?>
