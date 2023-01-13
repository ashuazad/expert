<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
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
$phone_no = trim($postedData['phone']);
$type = trim($postedData['type']);
$result = array( 'success' => false, 'type' => 'none');
$query = false;
$OTP = rand(1234,9999);
if (isset($_SESSION['OTPtry']) && ($_SESSION['OTPtry']>3)) {
    $type = false;
}
switch($type) {
    case 'SU':
        $query = "SELECT phone_no FROM superadmin WHERE phone_no='".$phone_no."'";
        $_SESSION['id'] = 1;
        $_SESSION['USER_TYPE'] = 'SUPERADMIN';
        $resultLogin = mysql_query($query);
        if (mysql_num_rows($resultLogin)==1) {
            $result['success'] = true;
            $result['type'] = $_SESSION['USER_TYPE'];
            $dataRow = mysql_fetch_assoc($resultLogin);
            $_SESSION['OTP'] = $OTP;
            $_SESSION['OTPtry'] = 1;
        }
        break;
    case 'U':
        $query = "SELECT id, phone_no FROM login_accounts WHERE phone_no='".$phone_no."'";
        $_SESSION['USER_TYPE'] = 'EMPLOYEE';
        $resultLogin = mysql_query($query);
        if (mysql_num_rows($resultLogin)==1) {
            $result['success'] = true;
            $result['type'] = $_SESSION['USER_TYPE'];
            $dataRow = mysql_fetch_assoc($resultLogin);
            $_SESSION['id'] = $dataRow['id'];
            $_SESSION['OTP'] = $OTP;
            $_SESSION['OTPtry'] = 1;
        }
        break;
    default:
        break;        
}
if ($query) {
    $_SESSION['verifyPhone'] = $phone_no;
    $smsMessageBody = $_SESSION['OTP']." is your Expert account verification code." ;
    $smsMessageBody = urlencode($smsMessageBody);
    $apiList = getAvailableSMSAPI();
    $whatsappApiList = getAvailableWhatsappAPI();
    foreach ($apiList as $eachApi) {
        $resultApiCall[] = callAPI($eachApi, array('phone'=>$dataRow["phone_no"], 'text' => $smsMessageBody));
    }
    foreach ($whatsappApiList as $eachWhatsappApi) {
        $resultApiCall[] = callAPI($eachWhatsappApi, array('phone'=>$dataRow["phone_no"], 'text' => $smsMessageBody));
    }
}
echo json_encode($result);