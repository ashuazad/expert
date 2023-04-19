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
$postData = json_decode(file_get_contents("php://input"),true);
//print_r($postData);
$sqlIds = "'".implode("','",$postData['ids'])."'";
//exit;
$action = $postData['action'];
$result = array('success' => false);
switch ($action) {
    case 'APPROVE':
        $statusValue = 'APPROVED';
        break;
    case 'DISAPPROVE':
        $statusValue = 'DISAPPROVE';
        break;
}
$sql = "UPDATE `lead_quotation` SET `status` = '".$statusValue."' WHERE `lead_quotation`.`id` IN(".$sqlIds.")";
mysql_query($sql);
$result['success'] = (mysql_affected_rows()>0);
echo json_encode($result);