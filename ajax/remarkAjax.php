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
switch ($_POST['opt']) {
	case 'update':
		echo $dbObj->dataupdate(array('remark'=>trim($_POST['upateName'])) , 'due_fee_remark' , "remark" , trim($_POST['name']) );
		break;
	case 'add':
		echo $dbObj->dataInsert(array('remark'=>trim($_POST['addName'])) , 'due_fee_remark');
		break;
	case 'delete':
		echo $dbObj->delOne('due_fee_remark','remark',trim($_POST['delName']));
		break;	
	default:
		echo 0;
		break;
}
