<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 5/4/17
 * Time: 4:30 PM
 */
session_start();
require_once ('include/dbconfig.php');
require_once ('include/helpfulFunctions.php');
require_once('include/header.php');
$pid = $_POST['pid'];
$pdo = db_connect();
$stmt = $pdo -> prepare(
    "insert into Discussion (username, pid, aComment) values (:username, :pid, :aComment)"
);
$stmt -> bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
$stmt -> bindParam(":pid", $pid, PDO::PARAM_STR);
$stmt -> bindParam(":aComment", $_POST['aComment'], PDO::PARAM_STR);
try {
    $result = $stmt->execute();
} catch (Exception $e) {
    warningMessage($e -> getMessage());
}

if (isset($result)) {
    correctMessage("Add comment successfully, redirecting to project page...");
    echo "<meta http-equiv='refresh' content='2; url=project.php?pid=$pid'>";
} else {
    warningMessage("Unable to add comment");
}
require_once ('include/footer.html');