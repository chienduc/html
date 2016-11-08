<?php
#print_r($_SESSION);

# User log
$userId = 0;
if($_SESSION['userId']) {$userId = $_SESSION['userId']; $usertype = 1; }
if($_SESSION['store_userId']) {$userId = $_SESSION['store_userId']; $usertype = 2; }

if($_SESSION['store_customerId']) {$userId = $_SESSION['store_customerId']; }
clearUserLog($storeId,$userId,$usertype,$_SERVER['REMOTE_ADDR']);

unset($_SESSION['store_customerId']);
#header("location:/index.html");
header("location: ".$_SERVER['HTTP_REFERER']);
?>