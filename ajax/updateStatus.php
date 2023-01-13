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

$data = explode(",", $_POST['data']);
$dataId = '';
foreach($data as $each){
    if(trim($each) != ''){
        $dataId .= "'" . $each . "',";
    }
}
$dataId = rtrim($dataId,",");
echo $sql = "UPDATE admission SET status = 'Admission' WHERE regno IN(" . $dataId . ")";
mysql_query($sql);
echo 1;
?>
