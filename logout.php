<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/27/17
 * Time: 4:01 PM
 */
session_start();
session_destroy();
require_once ("include/helpfulFunctions.php");
require_once ("include/header.html");
correctMessage('Log out successfully, redirecting to homepage...');
echo "<meta http-equiv='refresh' content='2; url=index.php'>";
require_once ("include/footer.html");