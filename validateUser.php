<?php
session_start();
require_once ("include/dbconfig.php");
require_once("include/header.php");
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/27/17
 * Time: 11:52 AM
 */
if (!isset($_POST['login-form-username']) or !isset($_POST['login-form-password'])) {
    header("Location: login_register.php?msg=NoSuchUser");
}
$conn = db_connect();
$sql = 'call validateUser(:username, :password, @validity)';
$stmt = $conn -> prepare($sql);
$stmt -> bindParam(':username', $_POST['login-form-username'], PDO::PARAM_STR);
$stmt -> bindParam(':password', $_POST['login-form-password'], PDO::PARAM_STR);
//$validity = false;
//$stmt -> execute([':username' => $_SESSION['login-form-username'], ':password' => $_SESSION['login-form-password']]) or die ("Cannot execute validation on database.");
$validity = false;
try {
    $stmt -> execute();
    $validity = $conn -> query("select @validity") -> fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    warningMessage($e -> getMessage());
}
//$validity = $stmt -> fetch() or die ("Failed to fetch validation sql");
if (isset($validity['@validity'])) {
    $_SESSION['username'] = $_POST['login-form-username'];
    $_SESSION['userpass'] = $_POST['login-form-password'];
    correctMessage('Log in successfully, redirecting to your homepage...');
    echo "<meta http-equiv='refresh' content='2; url=index.php'>";
} else {
    warningMessage('Log in failed, wait for redirecting.....');
    echo "<meta http-equiv='refresh' content='2; url=login_register.php'>";
}
require_once ("include/footer.html");