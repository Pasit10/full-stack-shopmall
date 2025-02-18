<?php
    require "repository/repo_stock.php";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(AddTransaction()){
            header("Location: homepage.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
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

    .card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 80vh;
    }

    .card {
        width: 400px;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        background: #ffffff;
        text-align: center;
    }

    .card h1 {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .btn-container {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 20px;
    }

    .btn-confirm,
    .btn-cancel {
        padding: 12px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        border: none;
    }

    .btn-confirm {
        background: #28a745;
        color: white;
        border: none;
    }

    .btn-confirm:hover {
        background: #218838;
        transform: scale(1.05);
        text-decoration: none;
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
        border: none;
    }

    .btn-cancel:hover {
        background: #c82333;
        transform: scale(1.05);
        text-decoration: none;
    }
</style>

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
            <h1>Confirm</h1>
            <div class="btn-container">
                <form method="POST" action="confirm.php">
                    <button type="submit" class="btn-confirm">Confirm</button>
                </form>
                <form>
                    <button type="button" class="btn-cancel" onclick="location.href='cart.php'">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>