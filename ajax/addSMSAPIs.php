<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
require_once '../includes/communication.php';
 session_start();
if(empty($_SESSION['id'])){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$studentObj = new student();
$commObj = new communication();
$today=date('ymd');

$errors = array();
$result = array();
$result['success'] = false;
$result['errors'] = $errors;
$postedData = json_decode(file_get_contents('php://input'), true);
$apiClass = array('WHATSUP','CALL','SMS');
//$type = 'LOGIN_OTP';
$api_type = array(
    'login' => 'LOGIN_OTP',
    'due' => 'DUE_FEES',
    'ivr' => 'IVR_CALL',
    'callnow' => 'CALL_NOW'
 );
$current_api_type = $api_type['login'];
if (isset($postedData['type'])) {
    $current_api_type = $api_type[trim($postedData['type'])];
}

if (!in_array($postedData['className'],$apiClass)) {
    $errors[] = 'Invalid API Class';
}
if (!count($errors)) {
    $insertData = array('api'=>$postedData['api'],'type'=>$current_api_type, 'class' => $postedData['className'], 'status' => $postedData['status']);
    $resultInsert = $dbObj->dataInsert($insertData, 'sms_api');
    if ($postedData['status'] && in_array($apiDetails[1]['type'], array('IVR_CALL','CALL_NOW'))) {
        $resultInsert = $commObj->markDefaultPerType($resultInsert, $current_api_type);
    }
    if ($resultInsert) {
        $result['success'] = true;
        $result['id'] = $resultInsert;
    }
}
$result['errors'] = $errors;
echo json_encode($result);