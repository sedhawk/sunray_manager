<?php 
session_start();
$validUsers = array("dws54", "plg9", "bl96",  "sf9", "mdw76", "cwb32");
$username =  $_SERVER['REMOTE_USER'];
if (!in_array($username, $validUsers)) die("NOT AUTHORIZED");
?>

