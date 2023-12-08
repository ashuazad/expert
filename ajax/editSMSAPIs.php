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
$type = 'LOGIN_OTP';
$id = trim($postedData['id']);
//$smsDetails = $dbObj->getData(array('1'),'sms_api', "id = '".$id."'");
$apiDetails = $commObj->validateAPI($id);
if (!in_array($postedData['className'], $apiClass)) {
    $errors[] = 'Invalid API Class';
}
if ($apiDetails[0] == 0) {
    $errors[] = 'SMS API Not Found';
}
if (!count($errors)) {
    if ($postedData['status'] && in_array($apiDetails[1]['type'], array('IVR_CALL','CALL_NOW'))) {
        $resultUpdate = $commObj->markDefaultPerType($id, $apiDetails[1]['type']);
        $updateData = array('api'=>$postedData['api'],'class' => $postedData['className'], 'status' => $postedData['status']);
        $resultUpdate = $dbObj->dataupdate($updateData, 'sms_api', "id", $id);
    } else {
        $updateData = array('api'=>$postedData['api'],'class' => $postedData['className'], 'status' => $postedData['status']);
        $resultUpdate = $dbObj->dataupdate($updateData, 'sms_api', "id", $id);
    }
    if ($resultUpdate) {
        $result['success'] = true;
        $result['id'] = $resultUpdate;
    }
}
$result['errors'] = $errors;
echo json_encode($result);