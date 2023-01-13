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
$brnObj = new managebranchDatabase();
$dbObj=new db();
$_POST['course']=implode("+",$_POST['course']);
$regno = $_POST['regno'];
unset($_POST['regno']);
unset($_POST['discount']);
unset($_POST['creditAmt']);
$_POST['next_due_date'] = date('Y-m-d',strtotime($_POST['next_due_date']));
if($dbObj->dataupdate($_POST , "admission" , "regno" , $regno )){
echo 1;
} else {
echo 0;
}
?>