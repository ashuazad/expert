<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
require_once '../includes/config.php';
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
$objConfig = new config();
if(!empty($_POST['key']) && isset($_POST['key'])){
    $value =  filter_var($_POST['key'], FILTER_VALIDATE_BOOLEAN) == 1 ? 1 : 0;
    
//echo $value ;
    $objConfig->setConfigKey('apiKey',$value,'whatsup');
}

