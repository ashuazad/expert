<?php
error_log("\nSMS",3,'smslog.log');
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
//Include Lib
require_once '../lib/verifyOtp.php';
session_start();
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$phone_no = trim($_GET['phone']);
$result = array();
//print_r($_SESSION);
//$otp = (trim($_GET['amd']) === '1')?$_SESSION['optSession']['opt']:$_SESSION['OTP'];
$otp = (isset($_SESSION['optSession']['amd']))?$_SESSION['optSession']['opt']:$_SESSION['OTP'];
if (empty($_SESSION['OTPtry']) && !isset($_SESSION['OTPtry'])) {
    $_SESSION['OTPtry'] = 1;
}
header('Content-Type: application/json; charset=utf-8');
if ($_SESSION['OTPtry']>3) {
    //Logging Out the User if retry more than 3 time
    unset($_SESSION['OTPtry']);
    unset($_SESSION['id']);
    unset($_SESSION['USER_TYPE']);
    session_destroy();
    session_regenerate_id();
    session_destroy();
    setcookie(session_name(),'',time()-3600);
    echo json_encode(array('success'=>false));
    exit();
    //Logging Out the User if retry more than 3 time
}
if (isset($otp) && !empty($otp) && isset($phone_no) && !empty($phone_no)) {
    $smsMessageBody = $otp." is your Expert account verification code." ;
    $smsMessageBody = str_replace(" ","%20",$smsMessageBody);
    $apiList = getAvailableSMSAPI();
    foreach ($apiList as $eachApi) {
       $result[] = callAPI($eachApi, array('phone'=>$phone_no, 'text' => $smsMessageBody));
    }
    $_SESSION['OTPtry'] = $_SESSION['OTPtry']+1;
}
echo json_encode(array('success'=>true));