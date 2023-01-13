<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
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
$today=date('ymd');

$errors = array();
$result = array();
$result['success'] = false;
$result['errors'] = $errors;
$postedData = json_decode(file_get_contents('php://input'), true);
$id = trim($postedData['id']);
$smsDetails = $dbObj->getData(array('1'),'sms_api', "id = '".$id."'");
if ($smsDetails[0] == 0) {
    $errors[] = 'SMS API Not Found';
}
if (!count($errors)) {
    $resultDelete = $dbObj->delOne( 'sms_api', "id", $id);
    if ($resultDelete) {
        $result['success'] = true;
        $result['id'] = $id;
    }
}
$result['errors'] = $errors;
echo json_encode($result);