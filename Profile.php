<?php
    require "repository/repo_customer.php";
    require "repository/repo_stock.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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

        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 85%;
        }

        .card {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: 90%;
            height: 95%;
            margin-top: 3%;
            padding: 25px;
            gap: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            box-sizing: border-box;
        }

        .card-detail-profile {
            flex: 30%;
            background: #f9f9f9;
            border-radius: 15px;
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .imgprofile {
            font-size: 150px;
            color: #4e73df;
            margin-bottom: 20px;
            display: flex; 
            justify-content: center; 
            align-items: center; 
        }

        .profile-info {
            font-size: 16px;
            color: #333;
            margin-top: 30px;
        }

        .profile-info p {
            margin: 12px 0;
            font-weight: 500;
        }

        .card-detail-history {
            flex: 68%;
            background: #f9f9f9;
            border-radius: 15px;
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-height: 100%; /* กำหนดความสูงสูงสุด */
            overflow-y: auto; /* แสดงแถบเลื่อนเมื่อเนื้อหามากเกิน */
        }

        .card-detail-history h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .history-content {
            font-size: 16px;
            color: #333;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f1f1f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .history-content p {
            margin: 10px 0;
        }

        .history-action-button {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .history-action-button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
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
    <div class="card-container">
        <div class="card">
            <!-- Profile Section -->
            <?php
                $IDCust = $_SESSION['IDCust'];

                $Cust_data = getCustomerByID($IDCust);
                $name = $Cust_data["Custname"];
                $sex = $Cust_data["Sex"];
                $address = $Cust_data["Address"];
                $tel = $Cust_data["Tel"];

                echo '<div class="card-detail-profile">
                        <i class="fas fa-user imgprofile"></i>
                        <div class="profile-info">
                            <p>Name: '. $name .'</p>
                            <p>Phone: '. $tel .'</p>
                            <p>Address: '. $address .'</p>
                            <p>Gender:'. $sex .'</p>
                        </div>
                    </div>';
        
                $transactions = GetTranscation();

                echo '<div class="card-detail-history">
                        <h3>History</h3>
                    ';
                foreach ($transactions as $transaction){
                    $id = $transaction['IDTransaction'];
                    $time = $transaction["Timestamp"];
                    $totalprice = $transaction["TotalPrice"];

                    echo '<div class="history-content">
                        <div>
                            <p>เวลา '. $time .' </p>
                            <p>ราคารวม '. $totalprice .'</p>
                            <p>รายการ:</p>
                    ';
                    $transactions_detail = GetTransactionDetail($id);
                    foreach ($transactions_detail as $trandetail){
                        $name = $trandetail["ProductName"];
                        $price_novat = $trandetail["PRICE_NOVAT"];
                        $vat = $trandetail["VAT"];
                        $qty = $trandetail["QTY"];

                        echo '
                            <p>'. $name .' จำนวน: '. $qty .' ราคาไม่รวม vat '. $price_novat .'บาท vat '. $vat .'บาท</p>
                        ';
                    }
                    
                    echo '</div>
                          <a href="printreceipt.php?id='.$id.'"><button class="history-action-button">Download</button></a>
                        </div><br>';
                }

                echo '</div>';
            ?>
        </div>
    </div>
</body>

</html>