<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/categoryDatabase.php';
require_once '../includes/userqueryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
session_start();
if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$dbObj = new db();
$status = $dbObj->getData( array("*") , "status" );
array_shift($status);
echo json_encode($status);