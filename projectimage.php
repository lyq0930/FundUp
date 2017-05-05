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

$pdo = db_connect();
if (isset($_GET['updateTime'])) {
    $stmt = $pdo -> prepare("Select * from ProjectDetails where pid = :pid and updateTime = :updateTime");
    $stmt -> execute([':pid' => $_GET['pid']], [':updateTime' => $_GET['pid']]);
} else {
    $stmt = $pdo->prepare("SELECT cover FROM Project WHERE pid = :pid");
    $stmt->execute([':pid' => $_GET['pid']]) or die("Cannot get image(s) for the project");
}
$result = $stmt -> fetch();
if (!isset($result['cover'])) {
    echo file_get_contents("images/default_image.jpeg");
} else {
    echo $result['cover'];
}
