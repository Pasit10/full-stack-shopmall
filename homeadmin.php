<?php
require "repo_admin.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['IDTransaction'], $_POST['IDStatus'], $_POST['action'])) {
        $idTranx = $_POST['IDTransaction'];
        $idStatus = (int) $_POST['IDStatus'];
        $action = $_POST['action'];

        // Update the status based on the action
        if ($action === 'accept') {
            if ($idStatus < 5) {
                $idStatus += 1; // Increment status for "Accept"
            }
        } else if ($action === 'reject') {
            if ($idStatus > 3) {
                $idStatus -= 1;
            }
        } else if ($action === 'cancel') {
            $idStatus = 6;
        }

        UpdateTransaction($idTranx, $idStatus);
        header("Location: " . $_SERVER['PHP_SELF']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html,
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .navbar {
            display: flex;
            align-items: center;
            padding: 20px 5%;
            background-color: #ffffff;
            border-bottom: 3px solid #ddd;
        }

        .navbar a {
            text-decoration: none;
        }

        .homepage a {
            font-size: 28px;
            color: #333;
            text-decoration: none;
            padding-left: 5%;
            font-weight: 600;
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

        .cart-profile-button:hover {
            background: #0d1926;
            transform: scale(1.05);
        }

        .button button {
            font-size: 16px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .button button:hover {
            background-color: #007bff;
            color: white;
            transform: scale(1.05);
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-table th,
        .order-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .order-table th {
            background-color: #4CAF50;
            color: white;
        }

        .order-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .order-table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-edit {
            background-color: greenyellow;
        }

        .btn-delete {
            background-color: #f44336;
        }

        .btn-edit:hover {
            background-color: #e68900;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            height: 100%;
            /* ใช้พื้นที่เต็มเซลล์ */
        }

        .actions button {
            flex: 1;
            /* ปรับขนาดปุ่มให้ยืดตามขนาดคอลัมน์ */
            height: 100%;
            /* ใช้พื้นที่เต็มในแนวตั้ง */
            min-height: 50px;
            /* ความสูงขั้นต่ำ */
            padding: 10px;
            /* เพิ่มพื้นที่ภายในปุ่ม */
            box-sizing: border-box;
            /* รวม padding เข้าไปในการคำนวณขนาด */
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="homepage">
            <a href="homeadmin.php">Homepage</a>
        </div>
        <div class="ml-auto">
            <a href="selectReport.php">
                <button type="button" class="cart-profile-button">
                    <i class="fas fa-file-alt"></i>
                    Report
                </button>
            </a>
        </div>
    </div>
    <div class="container">
        <h1>Admin - Access Orders</h1>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Customer Name</th>
                    <th>Products</th>
                    <th>Total Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $transactions = GetTransactionAdmin();

                foreach ($transactions as $transaction) {
                    echo "<tr>";
                    echo "<td>" . $transaction['IDTransaction'] . "</td>";
                    echo "<td>" . $transaction['customer_name'] . "</td>";
                    echo "<td style='white-space: pre-line;'>" . $transaction['products'] . "</td>";
                    echo "<td>" . $transaction['total_quantity'] . "</td>";
                    echo "<td>$" . number_format($transaction['TotalPrice'], 2) . "</td>";
                    echo "<td>" . $transaction['status'] . "</td>";

                    if ($transaction['IDStatus'] >= 5) {
                        continue;
                    }
                    echo "  
                            <td class='actions'>
                                <form class='order-form' method='POST' action='homeadmin.php'>
                                    <input type='hidden' name='IDTransaction' value='" . $transaction['IDTransaction'] . "'>
                                    <input type='hidden' name='IDStatus' value='" . $transaction['IDStatus'] . "'>
                                    <button class='btn btn-edit' type='submit' name='action' value='accept'>Accept</button>
                                    <button class='btn btn-edit' type='submit' name='action' value= 'reject'>Reject</button>
                                    <button class='btn btn-delete' type='submit' name='action' value='cancel'>Cancel</button>
                                </form>
                            </td>
                        ";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>