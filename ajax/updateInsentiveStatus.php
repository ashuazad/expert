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
$postData = json_decode(file_get_contents("php://input"));
foreach($postData as $key=>$value){
    $updateDate = array('insentive_amt' => $value, 'insentive_date' => date('Y-m-d'), 'insentive_status' => 'Approved');
    $dbObj->dataupdate($updateDate, "admission" , "roll_no" , $key );
}
?>
