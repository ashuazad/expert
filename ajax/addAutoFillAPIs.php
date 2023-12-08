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
if (!count($errors)) {
    $insertData = array('api'=>$postedData['api'], 'status' => $postedData['status']);
    $resultInsert = $dbObj->dataInsert($insertData, 'auto_fill_apis');
    if ($resultInsert) {
        $result['success'] = true;
        $result['id'] = $resultInsert;
    }
}
$result['errors'] = $errors;
echo json_encode($result);