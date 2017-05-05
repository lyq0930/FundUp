<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/26/17
 * Time: 4:27 PM
 */
require_once("include/helpfulFunctions.php");

function db_connect() {
    $servername = "localhost:3306";
    $username = "root";
    $password = "920930";
    try {
        $conn = new PDO("mysql:host=$servername; dbname=FundUp", $username, $password);
        $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        warningMessage("Connection failed, catched an PDOException." . $e->getMessage());
    }
    if (!$conn) {
        warningMessage("Failed to connect database, no connection has been established.");
        return false;
    }
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

?>