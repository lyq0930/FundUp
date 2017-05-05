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
$pid = $_POST['pid'];
$username = $_SESSION['username'];
if (isset($_POST['card-number-selecter'])) {
    $cardNum = $_POST['card-number-selecter'];
} else {
    $cardNum = $_POST['card-number'];
}
$amount = $_POST['pledgeAmount'];
$pdo = db_connect();

$log = $pdo -> prepare(
    "insert into log(username, operation, target)
                   values (:username, :operation, :target)"
);
$operation = "pledge";
$log -> bindParam(":username", $username, $pdo::PARAM_STR);
$log -> bindParam(":operation", $operation, $pdo::PARAM_STR);
$log -> bindParam(":target", $pid, $pdo::PARAM_INT);
$log -> execute();

if (isset($_POST['name-on-card'])) {
    $nameoncard=$_POST['name-on-card'];
    $expiration=$_POST['expiration-date'];
    $cvv=$_POST['cvv'];
    $billAdd=$_POST['billingAdd'];
    $city=$_POST['city'];
    $state=$_POST['state'];
    $zip=$_POST['zip'];
    $stmt = $pdo -> prepare(
        "insert into payment(username, cardNum, nameOnCard, cardExp, cardCvv, billingAddress, billingCity, billingState, billingZip)
                   values (:username, :cardNum, :nameOnCard, :cardExp, :cardCvv, :billingAddress, :billingCity, :billingState, :billingZip)");
    try {
//        $stmt->execute([':username' => $username],
//            [':cardNum' => $cardNum],
//            [':nameOnCard' => $nameoncard],
//            [':cardExp' => $expiration],
//            [':cardCvv' => $cvv],
//            [':billingAddress' => $billAdd],
//            [':billingCity' => $city],
//            [':billingState' => $state],
//            [':billingZip' => $zip]
//        ) or die("cannot insert new payment".$stmt->errorCode());
        $stmt -> bindParam(":username", $username, $pdo::PARAM_STR);
        $stmt -> bindParam(":cardNum", $cardNum, $pdo::PARAM_STR);
        $stmt -> bindParam(":nameOnCard", $nameoncard, $pdo::PARAM_STR);
        $stmt -> bindParam(":cardExp", $expiration, $pdo::PARAM_STR);
        $stmt -> bindParam(":cardCvv", $cvv, $pdo::PARAM_STR);
        $stmt -> bindParam(":billingAddress", $billAdd, $pdo::PARAM_STR);
        $stmt -> bindParam(":billingCity", $city, $pdo::PARAM_STR);
        $stmt -> bindParam(":billingState", $state, $pdo::PARAM_STR);
        $stmt -> bindParam(":billingZip", $zip, $pdo::PARAM_STR);
        $stmt -> execute();
    } catch (Exception $e) {
        echo $e -> getMessage();
    }
}

$stmt = $pdo -> prepare (
    "call CreateFund(:username, :pid, :fundAmount, :cardNumber, @actualAmount)");
//$result = $stmt -> execute([':username' => $username], [':pid' => $pid], [':cardNum' => $cardNum], ['amount' => $amount]);
$stmt -> bindParam(":username", $username, $pdo::PARAM_STR);
$stmt -> bindParam(":pid", $pid, $pdo::PARAM_INT);
$stmt -> bindParam(":fundAmount", $amount, $pdo::PARAM_STR);
$stmt -> bindParam(":cardNumber", $cardNum, $pdo::PARAM_STR);
try {
    $result = $stmt->execute();
} catch (Exception $e) {
}
$actualAmount = $pdo -> query("select @actualAmount") -> fetch();

if (isset($result)) {
    correctMessage("Pledge succeed! ". "You actually pledged " . $actualAmount['@actualAmount'] ." Redirecting to project page");
    echo "<meta http-equiv='refresh' content='5; url=project.php?pid=$pid'>";
} else {
    warningMessage("Sorry, you have pledged before.");
    echo "<meta http-equiv='refresh' content='5; url=project.php?pid=$pid'>";

}
require_once ('include/footer.html');
?>