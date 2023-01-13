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

$emp_id = mysql_real_escape_string($_POST['emp']);
$select = explode(", " , rtrim(mysql_real_escape_string($_POST['select']),', '));

foreach( $select as $regno ){	
	$dbObj->dataupdate(array("followup_emp_id" => $emp_id) , "admission", "regno", trim($regno));
}