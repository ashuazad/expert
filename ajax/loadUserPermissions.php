<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
require_once '../v11/lib/utilities.php';
require_once '../includes/userPermissions.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
if(!empty($_SESSION['uid'])){
    $user_id = $_SESSION['uid']; 
 }else{
    $user_id = $_SESSION['id']; 
 }
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$loadDeadAdmissions = false;
$userType = null;
$isPermissionEnable = false;
//Loading User Permissions
$permissions = new userPermissions($user_id);
$_SESSION['user_permission'] = $permissions->userPermission;