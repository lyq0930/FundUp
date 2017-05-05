<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/28/17
 * Time: 6:45 PM
 */
session_start();
require_once ('include/helpfulFunctions.php');
require_once ('include/dbconfig.php');
require_once ('include/header.html');
$img = $_FILES['file'];
$imgData =  file_get_contents($img['tmp_name']);
try {
    $pdo = db_connect();
    $stmt = $pdo -> prepare("call createProject(:username, :pname, :pdescription, :tags, :endFundTime, :completionDate, :minFund, :maxFund, :media)");
    $stmt -> bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
    $stmt -> bindParam(":pname", $_POST['project-name'], PDO::PARAM_STR);
    $stmt -> bindParam(":pdescription", $_POST['project-description'], PDO::PARAM_STR);
    $stmt -> bindParam(":tags", $_POST['project-tags'], PDO::PARAM_STR);
    $stmt -> bindParam(":endFundTime", $_POST['project-endfund-date'], PDO::PARAM_STR);
    $stmt -> bindParam(":completionDate", $_POST['project-complete-date'], PDO::PARAM_STR);
    $stmt -> bindParam(":minFund", strval($_POST['project-minfund']), PDO::PARAM_STR);
    $stmt -> bindParam(":maxFund", strval($_POST['project-maxfund']), PDO::PARAM_STR);
    $stmt -> bindParam(":media", $imgData, PDO::PARAM_LOB);
    $result = $stmt->execute();
} catch (Exception $e) {
    warningMessage($e -> getMessage());
}

if (isset($result)) {
    correctMessage("Create project successfully! Redirecting to homepage...");
    echo "<meta http-equiv='refresh' content='2; url=index.php'>";
}
require_once ('include/footer.html');
?>