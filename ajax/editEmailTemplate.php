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
//print_r($_POST);

$dataUpdate = array('email_title' => $_POST['email_title'] , 'email_content' => $_POST['email_content'] ) ;
$emailDeltails = $dbObj -> dataupdate($dataUpdate, 'email_template' , 'email_title' , $_POST['email_title_pk']);
