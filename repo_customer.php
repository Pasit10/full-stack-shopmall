<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $mysqli = mysqli_connect("localhost", "root", "", "shopmall", 3306);
    $_SESSION["mysqli"] = $mysqli;

    function addCustomer($name,$passwd,$sex,$address,$tel):bool {
        $mysqli = $_SESSION["mysqli"];

        $sql = "INSERT INTO Customer (Custname,Password, Sex, Address, Tel) VALUES ('$name','$passwd', '$sex', '$address', '$tel')";

        $stmt = mysqli_prepare($mysqli,$sql);
        if (!mysqli_stmt_execute($stmt)) {
            echo "error";
        } else {
            return true;
        }
        return false;
    }

    function login($name, $paswd):bool{
        $mysqli = $_SESSION["mysqli"];
        $sql = "SELECT IDCust,password FROM Customer WHERE Custname = '$name'";
        $result = mysqli_query($mysqli,$sql);
        if (!$result) {
            echo "Error in query execution: " . mysqli_error($mysqli);
            return false;
        }

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $password = $row['password'];
            if ($password == $paswd) {
                $_SESSION['IDCust'] =  $row['IDCust'];
                return true;
            }
        } else {
            echo "Name or password is wrong";
            return false;
        }
        return false;
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
