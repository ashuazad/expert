<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/userPermissions.php';
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
$today=date('ymd');
$insertData = array();
$errors = array();
$return = array('success'=>false);
$postData = json_decode(file_get_contents("php://input"),true);
$messageErrorFlag = 0;
$userObj = new userPermissions($postData['emp_id']);
//$rightsArray = array($postData['right']=>implode(',',$postData['user_ids']));
//$rightsArray = array($postData['right']=>implode(',',$postData['user_ids']));
$return['success'] = $userObj->setPermission($postData['rights'], $postData['emp_id']);
$return['success'] = true;
$return['result'] = $postData;
echo json_encode($return);