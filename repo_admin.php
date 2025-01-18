<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Establish database connection
    $mysqli = new mysqli("localhost", "root", "", "shopmall", 3306);
    $_SESSION["mysqli"] = $mysqli;

    function GetTranscation() {
        $mysqli = $_SESSION["mysqli"];

        $sql = "SELECT * FROM TRANSACTION";

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

    function GetTransactionStatus($IDStatus){
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT Name FROM TransactionStatus WHERE IDStatus = $IDStatus";

        $transaction_data = []
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }

    function getCustomerByID($CustId){
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT Custname, Sex, Address, Tel FROM Customer WHERE IDCust = $CustId";
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