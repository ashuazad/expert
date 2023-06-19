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
$where = " source != 'IVR' ";
if (count($postedData)) {
    $filterSQl = getLeadsFilterSQl($postedData);
    $where .= ' AND ' . $filterSQl;
    $countLeads = $dbObj->getData(array('count(*) AS RECORDS'),"leads",$where);
    $result['no_records'] = $countLeads[1]['RECORDS'];
    $result['success'] = true;
} else {
    $errors[] = 'Invalid Data';
}
$result['errors'] = $errors;
echo json_encode($result);