<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
require_once '../lib/utilities.php';
//Include Lib
require_once '../lib/verifyOtp.php';
session_start();
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
header('Content-Type: application/json; charset=utf-8');
$postedData = json_decode(file_get_contents('php://input'), true);
$otp = trim($postedData['otp']);
$result = array( 'success' => false, 'type' => 'none', 'error' => array());
$query = false;
$isValidPhone = false;
//Start Validations 
$sessKey = array('OTPtry', 'verifyPhone', 'OTP', 'id');
$errors = array();
// Check input OTP
if (!isset($otp) && empty($otp)) {
    destroySession($sessKey);
    $result['success'] = false;
    $errors[] = array('code'=>'OTP_EMPTY', 'message'=>'OTP is empty.');
}
//Check Max Attempt
$isValidPhone = true;               
if (isset($_SESSION['OTPtry']) && ($_SESSION['OTPtry']>3)) {
    destroySession($sessKey);
    $result['success'] = false;
    $errors[] = array('code'=>'MAX_TRY', 'message'=>'Maximum Attempt');
    
}
//Check Phone No
if (!isset($_SESSION['verifyPhone']) && empty($_SESSION['verifyPhone'])) {
    destroySession($sessKey);
    $result['success'] = false;
    $errors[] = array('code'=>'EMPTY_PHONE', 'message'=>'Empty Phone No');
}
//Validate Phone No
if (!count($errors)) {
    $phone_no = $_SESSION['verifyPhone'];
    $type = $_SESSION['USER_TYPE'];
    switch($type) {
        case 'SUPERADMIN':
            $query = "SELECT phone_no FROM superadmin WHERE phone_no='".$phone_no."'";
            $resultLogin = mysql_query($query);
            if (mysql_num_rows($resultLogin)) {
                $userDetails = mysql_fetch_assoc($resultLogin);
            }
            break;
        case 'EMPLOYEE':
            $query = "SELECT id, phone_no FROM login_accounts WHERE phone_no='".$phone_no."'";
            $resultLogin = mysql_query($query);
            if (mysql_num_rows($resultLogin)) {
                $isValidPhone = true; 
                $userDetails = mysql_fetch_assoc($resultLogin);
            }
            break;
        default:
            break;        
    }
    if (!$isValidPhone) {
        destroySession($sessKey);
        $result['success'] = false;
        $errors[] = array('code'=>'INVALID_PHONE', 'message'=>'Invalid Phone No');
    }
} 
//End Validations
//Verify OTP
if ($isValidPhone && ($_SESSION['OTP']==$otp)) {
    $result['success'] = true;
    $result['type'] = $type;
    $_SESSION['OTP_C'] = 1;
    $_SESSION['OTPtry'] = $_SESSION['OTPtry']+1;
    $_SESSION['user_details']['phone_no'] = $userDetails['phone_no'];
    unset($_SESSION['OTP']);
    //unset($_SESSION['USER_TYPE']);
    unset($_SESSION['verifyPhone']);
}
//Verify OTP
$result['error'] = $errors;
echo json_encode($result);