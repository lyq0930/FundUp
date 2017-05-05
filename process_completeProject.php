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
require_once ('include/header.php');
$pid = $_GET['pid'];
$pdo = db_connect();
$stmt = $pdo -> prepare (
    "update Project
               set pstatus = 'Completed'
               where pid = :pid");
$stmt -> bindParam(":pid", $pid, PDO::PARAM_INT);
try {
    $result = $stmt->execute() or die ("Unable to mark complete. " . $stmt->errorCode());
} catch (Exception $e) {
    echo $e -> getMessage();
}

if (isset($result)) {
    correctMessage("Mark project successfully! Redirecting to the project page...");
    echo "<meta http-equiv='refresh' content='2; url=project.php?pid=$pid'>";
}
require_once ('include/footer.html');
?>