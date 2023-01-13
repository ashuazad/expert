<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../includes/db.php';
require_once '../includes/sendSMS.php';
$smsObj = new sendSMS( $_POST['smsNo'] , $_POST['smsContent'] );
echo "<br>";
print_r($smsObj);

