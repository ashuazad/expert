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
$dataLst = ltrim($_POST['recptAry'],'+');
$dataPostArry = explode("+", $dataLst);
	$statusNew = 0;
	if($_POST['user']=='user'){
		$statusNew=1;
	}
	if($_POST['user']=='admin'){
		$statusNew=2;
	}
foreach($dataPostArry as $datRecpt)
{
	$datRecptDtl = explode("-",$datRecpt);
	$dbObj->dataupdate(array("send_status"=>$statusNew), "fee_detail", "f_id", $datRecptDtl[0]);	
}
