<?php
    require "repository/repo_stock.php";


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['qty'])) {
            foreach ($_POST['qty'] as $IDProduct => $newQty) {
                // ดึงข้อมูลสินค้าเพื่อรับ StockQty
                $product = getProductByID($IDProduct); // ฟังก์ชันที่ดึงข้อมูลสินค้า
                if ($product) {
                    $stockQty = $product['StockQtyFrontEnd'];

                    // ตรวจสอบจำนวนสินค้า
                    if ($newQty > $stockQty) {
                        $newQty = $stockQty; // จำกัดจำนวนให้อยู่ในสต็อก
                    } elseif ($newQty < 1) {
                        $newQty = 1; // อย่างน้อยต้องเลือก 1
                    }

                    // ตรวจสอบการเลือกสินค้า
                    $is_select = isset($_POST['selected_items'][$IDProduct]) ? 'T' : null;

                    // อัปเดตตะกร้าสินค้า
                    UpdateCart($IDProduct, $newQty, $is_select);
                }
            }

            // รีเฟรชหน้า
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['id'])) {
            $IDProduct = $_GET['id'];
            DeleteCart($IDProduct);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html, body {
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

        .container {
            margin-top: 50px;
        }

        .cart-table {
            margin-top: 30px;
        }

        .cart-table td,
        .cart-table th {
            padding: 12px;
        }

        .cart-table th {
            background-color: #007bff;
            color: white;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 20px;
            text-align: right;
        }

        .remove-item {
            color: red;
            cursor: pointer;
        }

        .checkbox {
            margin-right: 15px;
        }
    </style>
    <script>
        function validateQty(input, maxQty) {
            if (input.value > maxQty) {
                alert(`Quantity cannot exceed the available stock (${maxQty}).`);
                input.value = maxQty; // ปรับให้เป็นจำนวนสูงสุด
            } else if (input.value < 1) {
                alert("Quantity must be at least 1.");
                input.value = 1; // อย่างน้อยต้องมี 1
            }
        }
    </script>
</head>

<body>
    <div>
        <div class="navbar">
            <div class="homepage">
                <a href="homepage.php">Homepage</a>
            </div>
            <div class="ml-auto">
                <a href="cart.php">
                    <button type="button" class="btn btn-info cart-profile-button">
                        <i class="fas fa-shopping-cart"></i>
                        Cart
                    </button>
                </a>
                <a href="profile.php">
                    <button type="button" class="btn btn-info cart-profile-button">
                        <i class="fas fa-user"></i>
                        Profile
                    </button>
                </a>
            </div>
        </div>

        <div class="container">
            <h2>Your Shopping Cart</h2>
            <?php
                echo '<table class="table cart-table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>';
            
                $Carts = getCartByIDCust();
                $totalprice = 0;
                foreach($Carts as $item) {
                    $id = $item["IDProduct"];
                    $name = $item["ProductName"];
                    $priceperunit = $item["PricePerUnit"];
                    $qty = $item["Quantity"];
                    $stocks_qty = $item["StockQtyFrontEnd"];
                    $is_select = $item["IsSelect"];
                    $price = $priceperunit * $qty;
                    
                    if ($is_select == 'T') {
                        $totalprice += $price;
                    }
                    echo '
                        <form method="POST" action="">
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_items[' . $id . ']" class="checkbox" ' . ($is_select == 'T' ? 'checked="checked"' : '') . ' onchange="this.form.submit()">
                                    </td>
                                    <td>' . $name . '</td>
                                    <td>$' . $priceperunit . '</td>
                                    <td>
                                        <input type="number" name="qty[' . $id . ']" value="' . $qty . '" min="1" max="' . $stocks_qty . '" class="form-control" style="width: 80px;" onchange="this.form.submit();" oninput="validateQty(this, ' . $stocks_qty . ')">
                                    </td>
                                    <td>$' . $price . '</td>
                                    <td>
                                        <a href="cart.php?id='.$id.'">
                                            <span class="remove-item"><i class="fas fa-trash-alt"></i> Remove</span>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </form>
                        ';
                }
                
                echo '</table>';

                echo '<div class="total-price">
                        <p>Total: $'.$totalprice.'</p>
                    </div>';
            
                echo '<div class="d-flex justify-content-between">
                        <a href="homepage.php">
                            <button class="btn btn-custom">Continue Shopping</button>
                        </a>
                        <a href="confirm.php">
                            <button class="btn btn-custom">Proceed to Checkout</button>
                        </a>
                    </div>';
            ?>
        </div>
    </div>
</body>

</html>
