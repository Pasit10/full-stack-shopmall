<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $mysqli = mysqli_connect("localhost", "root", "", "shopmall", 3306);
    $_SESSION["mysqli"] = $mysqli;

    function login($name, $paswd):bool{
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT IDAdmin,password FROM Admin WHERE Adminname = '$name'";
        $result = mysqli_query($mysqli,$sql);
        if (!$result) {
            echo "Error in query execution: " . mysqli_error($mysqli);
            return false;
        }

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $password = $row['password'];
            if ($password == $paswd) {
                $_SESSION['IDAdmin'] =  $row['IDAdmin'];
                return true;
            }
        } else {
            echo "Name or password is wrong";
            return false;
        }
        return false;
    }

    function GetTransactionAdmin() {
        // Ensure the database connection is valid
        $mysqli = $_SESSION["mysqli"];
        if (!$mysqli) {
            die("Database connection not found in session.");
        }
    
        // SQL query
        $sql = "SELECT 
                    t.IDTransaction, 
                    c.Custname AS customer_name, 
                    GROUP_CONCAT(CONCAT(s.ProductName, ' (', td.QTY, ')') SEPARATOR '\n') AS products,
                    SUM(td.QTY) AS total_quantity, 
                    t.TotalPrice, 
                    ts.Name AS status
                FROM Transaction t
                INNER JOIN Customer c ON t.IDCust = c.IDCust
                INNER JOIN TransactionDetail td ON t.IDTransaction = td.IDtransaction
                INNER JOIN Stock s ON td.IDProduct = s.IDProduct
                INNER JOIN TransactionStatus ts ON t.IDStatus = ts.IDStatus
                GROUP BY 
                    t.IDTransaction, c.Custname, t.TotalPrice, ts.Name";
    
        // Execute query
        $result = mysqli_query($mysqli, $sql);
    
        // Check for errors in SQL execution
        if (!$result) {
            die("SQL Error: " . mysqli_error($mysqli));
        }
    
        // Process the result set
        $transaction_Data = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $transaction_Data[] = $row;
            }
        }
    
        return $transaction_Data;
    }

    function GetTransactionAdminLog() {

    }
?>
