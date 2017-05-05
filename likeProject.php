<?php
session_start();
require_once('include/header.php');
require_once ('include/dbconfig.php');
require_once ('include/helpfulFunctions.php');
$pid = $_GET['pid'];
$username = $_SESSION['username'];
$pdo = db_connect();

$operation = "like";
$log -> bindParam(":username", $username, $pdo::PARAM_STR);
$log -> bindParam(":operation", $operation, $pdo::PARAM_STR);
$log -> bindParam(":target", $pid, $pdo::PARAM_INT);
$log -> execute();

$stmt = $pdo -> prepare(
        "insert into UserLikes(username, pid) 
                   values(:username, :pid)");
$stmt -> bindParam(":username", $username, $pdo::PARAM_STR);
$stmt -> bindParam(":pid", $pid, $pdo::PARAM_INT);
$result = $stmt -> execute();
if (isset($result)) {
    correctMessage("Liked the project!");
    echo "<meta http-equiv='refresh' content='0; url=project.php?pid=$pid'>";
} else {
    warningMessage("Cannot like the project");
}


require_once ('include/footer.html');
?>