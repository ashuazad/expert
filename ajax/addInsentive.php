<?php
date_default_timezone_set('Asia/Kolkata');
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
$updateDate = array('insentive_amt' => $_POST['insentive_amt']);
if ($dbObj->dataupdate($updateDate, "admission" , "roll_no" , $_POST['roll_no'] )) {
    echo 1;
} else {
    echo 0;
}
?>
