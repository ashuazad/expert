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
$_POST = json_decode(file_get_contents('php://input'), true);
$_POST = explode(':',$_POST['smsTitle']);
if(count($_POST)>0){
    mysql_query("UPDATE sms SET default_sms_new_lead = 0");	
    foreach( $_POST as $eachVal ){
    	$updateArray = array('default_sms_new_lead' => 1);
    	$dbObj -> dataupdate( $updateArray , 'sms' , "sms_title" , $eachVal);
    }
}
