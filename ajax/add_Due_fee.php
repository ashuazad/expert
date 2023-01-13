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
$_POST['followup'] = date('Y-m-d H:i:s');
$_POST['user_id'] = $_SESSION['id'];

// Check Status
$admFeeDtl = $dbObj->getData(array("`total_fee`" , "`due_fee`"), 'admission' , "regno ='".$_POST['regno']."'" );
if($admFeeDtl[1][total_fee] > 2000){
	$amd_status = 'Admission';
}else{
	$amd_status = 'Registration';
}


if ($dbObj->dataInsert($_POST, "admission_followups")) {
	$admUpdate = array(
		'message' => $_POST['message'],
		'next_due_date' => $_POST['next_followup'],
		'status' => $amd_status,
		'followup_remark' => $_POST['remark'],
		'followup_emp_id' => $id,
		'last_followup_date' => $_POST['followup']
		);
	//if($dbObj->dataupdate(array( ), 'admission', 'regno', $_POST['regno'])){
	if ($dbObj->dataUpdate($admUpdate, "admission", "regno", $_POST['regno'])) {	
		echo 1;
	}else{
		echo 0;
	}
}else{
	echo 0;
}