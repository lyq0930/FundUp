<?php
session_start();
require_once ('include/header.html');
require_once ('include/dbconfig.php');
require_once ('include/helpfulFunctions.php');
$username = $_SESSION['username'];
$followee = $_GET['followee'];
$pdo = db_connect();
$stmt = $pdo -> prepare(
        "delete from UserFollow 
                   where username = :username and followee = :followee");
$stmt -> bindParam(":username", $username, $pdo::PARAM_STR);
$stmt -> bindParam(":followee", $followee, $pdo::PARAM_STR);
$result = $stmt -> execute();
if (isset($result)) {
    correctMessage("Unfollowed the person!");
    echo "<meta http-equiv='refresh' content='0; url=user.php?username=$followee'>";
} else {
    warningMessage("Cannot unfollow the person");
}


require_once ('include/footer.html');
?>