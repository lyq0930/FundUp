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
require_once('include/header.php');
$pid = $_SESSION['pid'];
$pdo = db_connect();
$stmt = $pdo -> prepare (
    "insert into ProjectDetails (pid, updateContent, updateDescription) 
               values (:pid, :updateContent, :updateDescription)"
);

$img = $_FILES['file'];
$updateContent =  file_get_contents($img['tmp_name']);
$updateDescription = $_POST['project-description'];
$stmt -> bindParam(":pid", $pid, PDO::PARAM_STR);
$stmt -> bindParam(":updateContent", $updateContent, PDO::PARAM_LOB);
$stmt -> bindParam(":updateDescription", $updateDescription, PDO::PARAM_STR);
try {
    $result = $stmt->execute() or die ("Unable to update. " . $stmt->errorCode());
} catch (Exception $e) {
    echo $e -> getMessage();
}

if (isset($result)) {
    correctMessage("Update project successfully! Redirecting to the project page...");
    echo "<meta http-equiv='refresh' content='2; url=project.php?pid=$pid'>";
}
require_once ('include/footer.html');
?>