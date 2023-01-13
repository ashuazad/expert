<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$today=date('Y-m-d');
$cityCd='';
$branchArry=$dbObj->getData(array('branch_name') ,"login_accounts","id='".$_POST['branch']."'" );
$branch=trim($branchArry[1]['branch_name']);
$cityCd=$branch[0];
$cityCd=$cityCd.strtoupper($branch[strpos($branch," ")+1]);
$lstId=$dbObj->getData(array( "*" ) ,"admission" ," date(doj)='".$today."'");
//print_r($lstId);
$newNo=(int)$lstId[0] + 1;
$today=date('ymd');
echo $regCode=$today.$cityCd.$newNo;