<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Shopping Mall</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1,
        h3 {
            color: #444;
            text-align: center;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="address"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }

        .products {
            margin: 15px 0;
        }

        .product-group {
            margin-bottom: 20px;
        }

        .product-group h4 {
            margin-bottom: 10px;
            color: #555;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        input[type="submit"],
        input[type="reset"] {
            padding: 10px 20px;
            margin: 10px 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
        }

        input[type="reset"] {
            background-color: #dc3545;
            color: #fff;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <form method="POST" action="register.php">
        <h3>Shopping Mall</h3>
        <h1>ฟอร์มการลงทะเบียน</h1>

        <label for="name">ชื่อผู้ใช้</label>
        <input type="text" id="name" name="name" placeholder="กรอกชื่อของคุณ" required>
        
        <label for="name">รหัสผ่าน</label>
        <input type="password" id="password" name="password" placeholder="กรอกรหัสของคุณ" required>

        <label for="tel">เบอร์โทรศัพท์</label>
        <input type="tel" id="tel" name="tel" placeholder="กรอกเบอร์โทรศัพท์ของคุณ" required>

        <label for="address">ที่อยู่</label>
        <input type="address" id="address" name="address" placeholder="กรอกที่อยู่ของคุณ" required>
        
        <div>
            <label >เพศ</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sex" id="sex" value="ชาย"
                checked>
                <label class="form-check-label" for="sex">
                    ชาย
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sex" id="sex" value="หญิง">
                <label class="form-check-label" for="exampleRadios2">
                    หญิง
                </label>
            </div>
        </div>

        <input type="submit" value="ยืนยัน">
        <input type="reset" value="ยกเลิก">
    </form>
</body>

</html>

<?php
    require "repo_customer.php";
    if (isset($_POST["name"]) && isset($_POST["password"]) && isset($_POST["sex"]) && isset($_POST["address"]) && isset($_POST["tel"])) {
        $name = $_POST["name"];
        $passwd = $_POST["password"];
        $address = $_POST["address"];
        $sex = ($_POST["sex"] === "ชาย") ? 'M':'F';
        $tel = $_POST["tel"];

        if(addCustomer($name,$passwd,$sex,$address,$tel)){
            header("Location: login.php");
        }
    }
?>