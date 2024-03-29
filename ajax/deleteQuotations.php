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
$postedData = json_decode(file_get_contents('php://input'), true);
//$ids = trim($postedData['ids']);
$idsSql = implode("', '",$postedData['ids']);
$deleteSql = "DELETE FROM lead_quotation WHERE id IN ('".$idsSql."')";
mysql_query($deleteSql);
$result['success'] = (mysql_affected_rows()>0);;
echo json_encode($result);