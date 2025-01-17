<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form ฟอร์มการสั่งซื้อสินค้าจาก Shopping Mall</title>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // รับค่าจากฟอร์ม
        $name = $_POST['name'] ?? 'ไม่มีข้อมูล';
        $tel = $_POST['tel'] ?? 'ไม่มีข้อมูล';
        $email = $_POST['email'] ?? 'ไม่มีข้อมูล';
        $Computer = isset($_POST['Computer']) ? $_POST['Computer'] : 'ไม่ได้เลือก';
        $Notebook = isset($_POST['Notebook']) ? $_POST['Notebook'] : 'ไม่ได้เลือก';

        // แสดงผล
        echo "<h3>ผลลัพธ์การสั่งซื้อ:</h3>";
        echo "ชื่อ: $name <br>";
        echo "เบอร์โทร: $tel <br>";
        echo "Email: $email <br>";
        echo "สินค้าที่ต้องการสั่งซื้อ: <br>";
        echo "$Computer<br>";
        echo "$Notebook<br>";
    }else if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // รับค่าจากฟอร์ม
        $name = $_GET['name'] ?? 'ไม่มีข้อมูล';
        $tel = $_GET['tel'] ?? 'ไม่มีข้อมูล';
        $email = $_GET['email'] ?? 'ไม่มีข้อมูล';
        $Computer = isset($_GET['Computer']) ? $_GET['Computer'] : 'ไม่ได้เลือก';
        $Notebook = isset($_GET['Notebook']) ? $_GET['Notebook'] : 'ไม่ได้เลือก';

        // แสดงผล
        echo "<h3>ผลลัพธ์การสั่งซื้อ:</h3>";
        echo "ชื่อ: $name <br>";
        echo "เบอร์โทร: $tel <br>";
        echo "Email: $email <br>";
        echo "สินค้าที่ต้องการสั่งซื้อ: <br>";
        echo "$Computer<br>";
        echo "$Notebook<br>";
    }
    ?>
</body>

</html>