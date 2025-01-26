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
    function getTopSellingReport() {
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT s.ProductName, SUM(td.QTY) AS TotalQuantitySold,SUM(td.PRICE_NOVAT * td.QTY) AS TotalRevenue 
                FROM TransactionDetail td 
                JOIN Stock s ON td.IDProduct = s.IDProduct 
                JOIN Transaction t ON td.IDtransaction = t.IDtransaction 
                WHERE t.IDStatus = 5 
                GROUP BY s.ProductName 
                ORDER BY TotalQuantitySold DESC 
                LIMIT 10;";

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

    function getOrderStatusReport() {
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT t.IDTransaction,c.Custname, ts.Name AS Status, t.TotalPrice,t.Timestamp 
        FROM Transaction t 
        JOIN Customer c ON t.IDCust = c.IDCust 
        JOIN TransactionStatus ts ON t.IDStatus = ts.IDStatus 
        ORDER BY t.Timestamp,t.IDTransaction DESC;";
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

    function getAdminActivityReport() {
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT al.IDAdmin, a.AdminName,ts.Name AS Status, al.Timestamp 
                FROM TransactionLog al 
                JOIN  ADMIN a ON al.IDAdmin = a.IDAdmin 
                JOIN TransactionStatus ts ON al.IDStatus = ts.IDStatus
                ORDER BY al.Timestamp DESC;";
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

    function getMonthlySalesReport() {
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT DATE_FORMAT(Timestamp, '%Y-%m') AS Month, SUM(TotalPrice) AS TotalSales 
                FROM Transaction 
                WHERE IDStatus = 5
                GROUP BY DATE_FORMAT(Timestamp, '%Y-%m') 
                ORDER BY Month;";
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

    function getRevenuebyProductReport() {
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT s.ProductName, (td.PRICE_NOVAT * td.QTY) AS Revenue 
                FROM TransactionDetail td 
                JOIN Stock s ON td.IDProduct = s.IDProduct 
                JOIN Transaction t ON td.IDtransaction = t.IDtransaction 
                WHERE t.IDStatus = 5 
                GROUP BY s.ProductName 
                ORDER BY Revenue DESC;";

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
    
    function getProfitandCostReport() {
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT s.ProductName,
                SUM(td.QTY) AS TotalQuantitySold,
                SUM(td.QTY * s.PricePerUnit) AS TotalRevenue,
                SUM(td.QTY * s.CostPerUnit) AS TotalCost,
                (SUM(td.QTY * s.PricePerUnit) - SUM(td.QTY * s.CostPerUnit)) AS GrossProfit,
                ROUND(((SUM(td.QTY * s.PricePerUnit) - SUM(td.QTY * s.CostPerUnit)) / SUM(td.QTY * s.PricePerUnit)) * 100, 2) AS GrossProfitMargin
                FROM 
                    TransactionDetail td
                JOIN 
                    Stock s ON td.IDProduct = s.IDProduct
                JOIN Transaction t ON td.IDtransaction = t.IDtransaction
                WHERE 
                    t.IDStatus = 5
                GROUP BY 
                    s.ProductName
                ORDER BY 
                GrossProfit DESC;";
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
?>