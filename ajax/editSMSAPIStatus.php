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
$id = trim($postedData['id']);
$apiDetails = $commObj->validateAPI($id);
if (!$apiDetails) {
    $errors[] = 'SMS API Not Found';
}
if (!count($errors)) {
    if ($apiDetails[1]['type'] == 'IVR_CALL') {
        $resultUpdate = $commObj->markDefaultPerType($id, $apiDetails[1]['type']);
    } else {
        $updateData = array('status' => ($smsDetails[1]['status'] == '1')?'0':'1');
        $resultUpdate = $dbObj->dataupdate($updateData, 'sms_api', "id", $id);
    }   
    if ($resultUpdate) {
        $result['success'] = true;
        $result['id'] = $id;
    }
}
$result['errors'] = $errors;
echo json_encode($result);