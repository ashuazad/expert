<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/userPermissions.php';

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
$userPermissions = new userPermissions($id);
//print_r($userPermissions->userPermission);
$today=date('ymd');
$cityCd='';
$phone=$_POST['phone'];

$leadDtl=$dbObj->getData(array("*") ,"leads" ," phone = '".$phone."'");
if($userPermissions->userPermission['adm_from_details_phone'] || ($leadDtl[1]['emp_id'] == $id ) ){
echo json_encode($leadDtl[1]);
}else{
echo json_encode(array());
}
//echo $leadDtl[1]['name']."-".$leadDtl[1]['phone']."-".$leadDtl[1]['email']."-".$leadDtl[1]['address']."-".$leadDtl[1]['emp_id'];
