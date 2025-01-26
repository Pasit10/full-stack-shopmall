<?php
require "repo_report.php";
require_once "vendor/autoload.php";

use Mpdf\Mpdf;

$orderStatusData = getOrderStatusReport();

if (isset($_GET['download_pdf'])) {
    $mpdf = new Mpdf();

    ob_start();
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th,
            table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            table th {
                background-color: #007bff;
                color: white;
            }

            .timestamp {
                text-align: right;
                font-size: 12px;
                color: #555;
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <h2 style="text-align: center;">Order Status Report</h2>
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orderStatusData)): ?>
                    <?php foreach ($orderStatusData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['IDTransaction']) ?></td>
                            <td><?= htmlspecialchars($row['Custname']) ?></td>
                            <td><?= htmlspecialchars($row['Status']) ?></td>
                            <td><?= number_format($row['TotalPrice'], 2) ?></td>
                            <td><?= htmlspecialchars($row['Timestamp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="timestamp">Generated on: <?= date("Y-m-d H:i:s") ?></div>
    </body>

    </html>
    <?php
    $html = ob_get_clean();
    $mpdf->WriteHTML($html);
    $mpdf->Output("Order_Status_Report.pdf", "D");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            align-items: center;
            padding: 20px 5%;
            background-color: #ffffff;
            border-bottom: 1px solid #ddd;
        }

        .homepage a {
            font-size: 24px;
            color: #333;
            text-decoration: none;
            font-weight: 600;
        }

        .report-container {
            margin: 40px auto;
            max-width: 800px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .report-header {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: left;
            padding: 12px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .cart-profile-button {
            background: #1e2a3a;
            font-size: 16px;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            margin-left: 15px;
            transition: all 0.3s ease;
        }

        .cart-profile-button a {
            text-decoration: none;
        }

        .cart-profile-button:hover {
            background: #0d1926;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="homepage">
            <a href="homeadmin.php">Homepage</a>
        </div>
        <div class="ml-auto">
            <a href="?download_pdf=1">
                <button type="button" class="cart-profile-button">
                    <i class="fas fa-file-alt"></i> Download
                </button>
            </a>
        </div>
    </div>

    <div class="report-container">

        <div class="report-header">Order Status Report</div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orderStatusData)): ?>
                    <?php foreach ($orderStatusData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['IDTransaction']) ?></td>
                            <td><?= htmlspecialchars($row['Custname']) ?></td>
                            <td><?= htmlspecialchars($row['Status']) ?></td>
                            <td><?= number_format($row['TotalPrice'], 2) ?></td>
                            <td><?= htmlspecialchars($row['Timestamp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>