<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
require_once '../includes/communication.php';
require_once '../includes/functions.php';
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
$jobStatus = $dbObj->getData(array('object_type','created_date','view_name','no_of_records','no_of_completed','status'),"delayed_auto_fill_jobs");
if ($jobStatus[0]>0) {
   mysql_query('DROP VIEW IF EXISTS ' . $jobStatus[1]['view_name']);
   mysql_query('DELETE FROM delayed_auto_fill_jobs');
   $result['errors'] = $errors;
   $result['success'] = true;
}
echo json_encode($result);