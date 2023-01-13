<?php
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
$delReg = explode("+",$_POST['dataDel']);
$c=0;
foreach($delReg as $regId){
$c= $dbObj->dataupdate(array("status"=>0),"admission","regno",$regId);
$c= $dbObj->delOne("fee_detail","reg_no",$regId);
}
if($c){
echo 1;
}