<?php
    include "repo_stock.php";
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Mall</title>
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

        .navbar a{
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

        .head {
            padding-top: 3%;
            text-align: center;
            margin-bottom: 40px;
        }

        .head h2 {
            font-size: 2.5rem;
            color: #333;
            font-weight: 600;
        }

        .product {
            display: flex;
            flex-wrap: wrap;/* ให้การ์ดพับไปหลายแถวตามขนาดหน้าจอ */
            justify-content: space-around;/* จัดการระยะห่างของการ์ด */
            gap: 20px;/* ระยะห่างระหว่างการ์ด */
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            flex: 1 1 250px;/* กำหนดให้การ์ดมีขนาดเริ่มต้น 250px และขยายได้ตามพื้นที่ */
            max-width: 300px;/* กำหนดขนาดสูงสุดของการ์ด */
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .card-img:hover {
            transform: scale(1.05);
        }

        .card-body {
            padding: 15px;
            background-color: #fff;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .card-detail {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 15px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
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
        <div class="head">
            <h2>ALL Product</h2>
        </div>
        <div class="product">
            <?php
                $stocks = getAllStocks();
                foreach ($stocks as $stock){
                    $id = $stock["IDProduct"];
                    $pdname = $stock["ProductName"];
                    $pdprice = $stock["PricePerUnit"];
                    $pddetali = $stock["Detail"];
                    $pdqty = $stock["StockQty"];
                    $pdimgpath = $stock["ProductImagePath"];

                    if($pdqty == 0){
                        continue;
                    }

                    echo '
                        <div class="card">
                            <img src=".'.$pdimgpath.'"class="card-img">
                            <div class="card-body">
                                <h5 class="card-title">'.$pdname.'</h5>
                                <p class="card-detail">'.$pddetali.'</p>
                                <form action="homepage.php" method="POST">
                                    <input type="hidden" name="IDProduct" value="'.$id.'">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    ';
                }
            ?>
        </div>
    </div>
    </div>
</body>

</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["IDProduct"])) {
            $IDProduct = intval($_POST["IDProduct"]);
            if (addCart($IDProduct)) {
                //echo "<script type='text/javascript'>alert('Product added to cart successfully!');</script>";
            } else {
                //echo "<script type='text/javascript'>alert('Failed to add product to cart.');</script>";
            }
        }
    }
?>

<!-- CREATE TABLE STOCK (
    IDProduct INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(50),
    PricePerUnit DECIMAL(8,2),
    Detail VARCHAR(255),
    StockQty INT,
    ProductImagePath VARCHAR(255)
); -->
