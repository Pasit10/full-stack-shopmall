<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Establish database connection
    $mysqli = new mysqli("localhost", "root", "", "shopmall", 3306);
    $_SESSION["mysqli"] = $mysqli;

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Function to retrieve all stocks
    function getAllStocks() {
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT * FROM Stock";
        $result = $mysqli->query($sql);

        $stocks = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $stocks[] = $row;
            }
        } else {
            // Optionally handle empty result or query error
            if ($mysqli->error) {
                error_log("MySQL error: " . $mysqli->error);
            }
        }

        return $stocks;
    }

    
    function getProductByID($IDProduct) {
        $mysqli = $_SESSION["mysqli"];

        // ค้นหาสินค้าตาม ID
        $sql = "SELECT * FROM stock WHERE IDProduct = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $IDProduct);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        } else {
            $product = null;
        }

        $stmt->close();
        return $product;
    }

    function getCartByIDCust(){
        $mysqli = $_SESSION["mysqli"];
        $IDCust = $_SESSION["IDCust"];
        $sql = "SELECT Cart.IDProduct,ProductName,PricePerUnit,Quantity,StockQtyFrontEnd,IsSelect
                FROM Cart INNER JOIN STOCK ON Cart.IDProduct = STOCK.IDProduct
                WHERE IDCust = $IDCust
                ";
        $result = $mysqli->query($sql);
        if (!$result) {
            echo "Error in query execution: " . mysqli_error($mysqli);
        }

        $result_data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $result_data[] = $row;
            }
        } else {
            // Optionally handle empty result or query error
            if ($mysqli->error) {
                error_log("MySQL error: " . $mysqli->error);
            }
        }
        return $result_data;
    }

    function addCart($IDProduct):bool{
        $mysqli = $_SESSION["mysqli"];
        $IDCust = $_SESSION["IDCust"];
        $qty = 1; // default = 1

        $checkstock_sql = "SELECT StockQtyFrontEnd FROM STOCK WHERE IDProduct = $IDProduct";
        $stock_qty = $mysqli->query($checkstock_sql)->fetch_assoc()["StockQtyFrontEnd"];
        // check primary key exits
        $sql = "SELECT * FROM Cart WHERE IDCust = $IDCust AND IDProduct = $IDProduct";
        $result = $mysqli->query($sql);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $update_qty = $row["Quantity"];
            $update_qty += 1;

            if ($update_qty > $stock_qty){
                return false;
            }

            $update_sql = "UPDATE Cart SET Quantity = $update_qty WHERE IDCust = $IDCust and IDProduct = $IDProduct";
            $update_stmt = $mysqli->prepare($update_sql);

            if(!mysqli_stmt_execute($update_stmt)) {
                return false;
            }
            return true;
        }

        $insert_sql = "INSERT INTO Cart (IDCust, IDProduct, Quantity) VALUES ($IDCust,$IDProduct,$qty)";

        $insert_stmt = $mysqli->prepare($insert_sql);
        if(!mysqli_stmt_execute($insert_stmt)) {
            return false;
        }else {
            return true;
        }
    }

    function UpdateCart($IDProduct,$qty,$is_select){
        $mysqli = $_SESSION["mysqli"];
        $IDCust = $_SESSION["IDCust"];
        $sql = "UPDATE Cart SET Quantity = $qty, IsSelect = '$is_select' WHERE IDCust = $IDCust and IDProduct = $IDProduct";

        $stmt = $mysqli->prepare($sql);
        if(!mysqli_stmt_execute($stmt)) {
            return false;
        }else {
            return true;
        }
    }

    function DeleteCart($IDProduct){
        $mysqli = $_SESSION["mysqli"];
        $IDCust = $_SESSION["IDCust"];
        $sql = "DELETE FROM Cart WHERE IDCust = $IDCust and IDProduct = $IDProduct";

        $stmt = $mysqli->prepare($sql);
        if(!mysqli_stmt_execute($stmt)) {
            return false;
        }else {
            return true;
        }
    }

    function AddTransaction(){
        $mysqli = $_SESSION["mysqli"];
        $IDCust = $_SESSION["IDCust"];

        $sql = "CALL AddTransactionDetails(?)";

        if ($stmt = $mysqli->prepare($sql)) {

            $stmt->bind_param("i", $IDCust);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        }
        return false;
    }

    function GetTranscation() {
        $mysqli = $_SESSION["mysqli"];
        $IDCust = $_SESSION["IDCust"];

        $sql = "SELECT * FROM TRANSACTION WHERE IDCust = $IDCust";

        $result = $mysqli->query($sql);
        if (!$result) {
            echo "Error in query execution: " . mysqli_error($mysqli);
        }

        $result_data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $result_data[] = $row;
            }
        } else {
            // Optionally handle empty result or query error
            if ($mysqli->error) {
                error_log("MySQL error: " . $mysqli->error);
            }
        }
        return $result_data;
    }

    function GetTransactionDetail($IDtranx){
        $mysqli = $_SESSION["mysqli"];

        $sql = "SELECT * FROM TransactionDetail INNER JOIN STOCK ON TransactionDetail.IDProduct = STOCK.IDProduct WHERE IDtransaction = $IDtranx";

        $result = $mysqli->query($sql);
        if (!$result) {
            echo "Error in query execution: " . mysqli_error($mysqli);
        }

        $result_data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $result_data[] = $row;
            }
        } else {
            // Optionally handle empty result or query error
            if ($mysqli->error) {
                error_log("MySQL error: " . $mysqli->error);
            }
        }
        return $result_data;
    }

    function GetTransactionsByID($IDtranx){
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT * FROM TRANSACTION WHERE IDtransaction = $IDtranx";
        $result = mysqli_query($mysqli,$sql);
        if (!$result) {
            echo "Error in query execution: " . mysqli_error($mysqli);
        }
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            return null;
        }
    }
?>