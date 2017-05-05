<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/27/17
 * Time: 4:19 PM
 */
session_start();
require_once ('include/dbconfig.php');
require_once ('include/helpfulFunctions.php');
require_once('include/header.php');
try {
    $conn = db_connect();
    $stmt = $conn->prepare("call CreateUser(:username, :password, :fullname, :email, :phoneNum)");
    $result = $stmt->execute([
        ':username' => $_POST['register-form-username'],
        ':password' => $_POST['register-form-password'],
        ':fullname' => $_POST['register-form-email'],
        ':email' => $_POST['register-form-email'],
        ':phoneNum' => $_POST['register-form-phone']]);
} catch (Exception $e) {
    warningMessage($e);
}
if (isset($result)) {
    correctMessage("Registered successfully, redirect to your homepage...");
    $_SESSION['username'] = $_POST['register-form-username'];
    echo "<meta http-equiv='refresh' content='2; url=index.php'>";
} else {
    warningMessage("Registration failed! Redirect to re-register...");
    echo "<meta http-equiv='refresh' content='2; url=login_register.php'>";
}
require_once ('include/footer.html');