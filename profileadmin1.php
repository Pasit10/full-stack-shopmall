<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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
                // Connect to the database
                $conn = new mysqli("localhost", "root", "", "shopmall");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT t.IDTransaction, c.Custname AS customer_name, 
                        GROUP_CONCAT(CONCAT(s.ProductName, ' (', td.QTY, ')') SEPARATOR '\n') AS products,
                        SUM(td.QTY) AS total_quantity, 
                        t.TotalPrice, ts.Name AS status
                        FROM Transaction t
                        INNER JOIN Customer c ON t.IDCust = c.IDCust
                        INNER JOIN TransactionDetail td ON t.IDTransaction = td.IDtransaction
                        INNER JOIN Stock s ON td.IDProduct = s.IDProduct
                        INNER JOIN TransactionStatus ts ON t.IDStatus = ts.IDStatus
                        GROUP BY t.IDTransaction, c.Custname, t.TotalPrice, ts.Name";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['IDTransaction'] . "</td>";
                        echo "<td>" . $row['customer_name'] . "</td>";
                        echo "<td style='white-space: pre-line;'>" . $row['products'] . "</td>";
                        echo "<td>" . $row['total_quantity'] . "</td>";
                        echo "<td>$" . number_format($row['TotalPrice'], 2) . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td class='actions'>
                                <button class='btn btn-edit'>Access</button>
                                <button class='btn btn-delete'>Cancel</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>