<?php
session_start();
require_once("include/header.html");
require_once("include/dbconfig.php");
require_once("include/helpfulFunctions.php");


$conn = db_connect();
if (!$conn) {
    warningMessage("Failed to connect database, no connection has been established.");
}

if (isset($_SESSION['username'])) {
    require_once("personalHomePage.php");
} else {
    require_once ("include/generalHomePage.html");
}

require_once("include/footer.html");