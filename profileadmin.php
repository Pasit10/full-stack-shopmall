<?php
    include "repo_admin";
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>ยินดีต้อนรับเข้าสู่หน้าจัดการคำสั่งซื้อ</h1>

    <table border="1">
        <thead>
            <tr>
                <th>หมายเลขคำสั่งซื้อ</th>
                <th>ชื่อผู้สั่งซื้อ</th>
                <th>สถานะ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $all_transcaction = GetTranscation();
                foreach($all_transcaction as $transcaction) {
                    $customer = 
                    echo "<tr>";
                    echo "<td>" . $transcaction['order_id'] . "</td>";
                    echo "<td>" . $row['customer_name'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td><a href='edit_order.php?id=" . $row['order_id'] . "'>แก้ไข</a> | <a href='delete_order.php?id=" . $row['order_id'] . "'>ลบ</a></td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>