<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/29/17
 * Time: 8:54 PM
 */
session_start();
require_once ('include/dbconfig.php');
header("Content-type: image/JPEG",true);
header("Content-type: image/PNG",true);

$pid = $_GET['pid'];
$pdo = db_connect();
if (isset($_GET['updateTime'])) {
    $updatTime = $_GET['updateTime'];
    $stmt = $pdo -> prepare("Select updateContent img from ProjectDetails where pid = :pid and updateTime = :updateTime");
    $stmt -> bindParam(":pid", $pid, $pdo::PARAM_INT);
    $stmt -> bindParam(":updateTime", $updatTime, $pdo::PARAM_STR);
    $stmt -> execute();
} else {
    $stmt = $pdo->prepare("SELECT cover img FROM Project WHERE pid = :pid");
    $stmt->execute([':pid' => $_GET['pid']]) or die("Cannot get image(s) for the project");
}
$result = $stmt -> fetch();
if (!isset($result['img'])) {
    echo file_get_contents("images/default_image.jpeg");
} else {
    echo $result['img'];
}
