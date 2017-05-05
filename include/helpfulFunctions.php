<?php
session_start();
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 4/26/17
 * Time: 5:05 PM
 */

function warningMessage($aMessage) {
    echo "
    <div class='alert alert-danger' role='alert'>
        <strong>Oh snap!</strong> $aMessage 
    </div>
    ";
}

function correctMessage($aMessage) {
    echo "
    <div class='alert alert-success' role='alert'>
        <strong>Well done!</strong> $aMessage
    </div>
    ";
}

?>