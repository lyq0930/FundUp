<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/28/17
 * Time: 10:28 AM
 */
session_start();
require_once('include/header.php');
require_once ('include/dbconfig.php');
$pdo = db_connect();
$username = $_SESSION['username'];
$pid = $_POST['pid'];
$stars = $_POST['stars'];
$rate = $pdo -> prepare(
    "insert into ProjectRate(username, pid, stars)
                   values (:username, :pid, :stars)"
);
$rate -> bindParam(":username", $username, $pdo::PARAM_STR);
$rate -> bindParam(":pid", $pid, $pdo::PARAM_INT);
$rate -> bindParam(":stars", $stars, $pdo::PARAM_INT);
try {
    $result = $rate -> execute();
} catch (Exception $e) {
    warningMessage("You have not pledge the project, cannot rate it.");
    echo "<meta http-equiv='refresh' content='5; url=project.php?pid=$pid'>";
}
if ($result == ture) {
    correctMessage("Rate Success.");
    echo "<meta http-equiv='refresh' content='0; url=project.php?pid=$pid'>";
}
require_once ('include/footer.html');
?>