<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
session_start();
if(!$_SESSION['id']){
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$record = array();
$_POST = json_decode(file_get_contents("php://input"),true);
if (!empty($_POST['id']) && isset($_POST['id'])) {
    $record=$dbObj->getData(array('id',"CONCAT(first_name, ' ', last_name) AS name"), 'login_accounts', "branch_id ='".$_POST['id']."'");
    array_shift($record);
}
echo json_encode($record);

