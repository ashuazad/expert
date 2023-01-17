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
$api_type = array(
   'login' => 'LOGIN_OTP',
   'due' => 'DUE_FEES'
);
$current_api_type = $api_type['login'];
if (isset($_GET['type'])) {
   $current_api_type = $api_type[trim($_GET['type'])];
}
$columnList = array('id as ID','api AS API','status AS STATUS','class AS CLASS','type AS TYPE');
$smsList = $dbObj -> getData($columnList, 'sms_api' , "type = '" . $current_api_type . "' AND class IN ('WHATSUP','CALL','SMS')");
array_shift($smsList);
echo json_encode($smsList);