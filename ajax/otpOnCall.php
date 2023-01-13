<?php
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
   $apiList = getAvailableCallAPI();
   foreach ($apiList as $eachApi) {
       $result[] = callAPI($eachApi, array('phone'=>$phone_no, 'text' => $otp));
   }
    $WhatsappMessageBody = $otp." is your Expert account verification code." ;
    $WhatsappMessageBody = urlencode($WhatsappMessageBody);
    $apiWhatsappList = getAvailableWhatsappAPI();
    foreach ($apiWhatsappList as $eachWhatsappApi) {
        $result[] = callAPI($eachWhatsappApi, array('phone'=>$phone_no, 'text' => $WhatsappMessageBody));
    }
    $_SESSION['OTPtry'] = $_SESSION['OTPtry']+1;
}
//print_r($result);
echo json_encode(array('success'=>true));