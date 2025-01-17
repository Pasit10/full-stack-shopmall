<?php
require_once __DIR__ . '/vendor/autoload.php';

require "repo_customer.php";
require "repo_stock.php";

// เริ่มสร้าง HTML สำหรับ PDF
$id = $_GET["id"];
$IDCust = $_SESSION['IDCust'];

$transaction = GetTransactionsByID($id);
$Cust_data = getCustomerByID($IDCust);
$html = "
    <style>
        body { font-family: 'Garuda', sans-serif; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 24px; margin: 0; }
        .customer-info { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .table th { background-color: #f2f2f2; }
        .footer { text-align: right; margin-top: 20px; }
    </style>

    <div class='container'>
        <div class='header'>
            <h1>Transaction Slip</h1>
            <p>Transaction ID: {$transaction['IDTransaction']}</p>
            <p>Date: " . date('Y-m-d H:i:s', strtotime($transaction['Timestamp'])) . "</p>
        </div>
        <div class='customer-info'>
            <p><strong>Customer Name:</strong> {$Cust_data["Custname"]}</p>
            <p><strong>Address:</strong> {$Cust_data["Address"]}</p>
            <p><strong>Phone:</strong> {$Cust_data["Tel"]}</p>
        </div>
        <table class='table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>";

$total = 0;
$transactions_detail = GetTransactionDetail($id);
foreach ($transactions_detail as $trandetail) {
    $lineTotal = $trandetail["PRICE"] * $trandetail["QTY"];
    $total += $lineTotal;
    $html .= "
        <tr>
            <td>{$trandetail['Seq']}</td>
            <td>{$trandetail["ProductName"]}</td>
            <td>{$trandetail["QTY"]}</td>
            <td>" . number_format($trandetail["PRICE"], 2) . "</td>
            <td>" . number_format($lineTotal, 2) . "</td>
        </tr>";
}

// คำนวณ VAT (7%) และยอดรวมสุทธิ
$vat = $total * 0.07;
$grandTotal = $total + $vat;

$html .= "
        </tbody>
    </table>
    <div class='footer'>
        <p><strong>Subtotal:</strong> " . number_format($total, 2) . "</p>
        <p><strong>VAT (7%):</strong> " . number_format($vat, 2) . "</p>
        <p><strong>Grand Total:</strong> " . number_format($grandTotal, 2) . "</p>
    </div>
</div>";

// สร้าง PDF ด้วย mPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Transaction_{$transaction['IDTransaction']}.pdf", "I");
?>