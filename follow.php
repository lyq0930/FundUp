<?php
session_start();
require_once ('include/header.html');
require_once ('include/dbconfig.php');
require_once ('include/helpfulFunctions.php');
$username = $_SESSION['username'];
$followee = $_GET['followee'];
$pdo = db_connect();
$stmt = $pdo -> prepare(
        "insert into UserFollow(username, followee) 
                   values(:username, :followee)");
$stmt -> bindParam(":username", $username, $pdo::PARAM_STR);
$stmt -> bindParam(":followee", $followee, $pdo::PARAM_STR);
$result = $stmt -> execute();
if (isset($result)) {
    correctMessage("Followed the person!");
    echo "<meta http-equiv='refresh' content='0; url=user.php?username=$followee'>";
} else {
    warningMessage("Cannot follow the person");
}


require_once ('include/footer.html');
?>