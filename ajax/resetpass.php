<?php
require_once '../includes/userqueryDatabase.php';
require_once  '../includes/useraccountDatabase.php';
require_once  '../includes/db.php';
date_default_timezone_set('Asia/Kolkata');
session_start();

if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
if(!empty($_SESSION['uid'])){
   $user_id = $_SESSION['uid']; 
}else{
   $user_id = $_SESSION['id']; 
}
$dbObj = new db();
$userquery = new userqueryDatabase();
$useraccount = new useraccountDatabase();
$fetchrecord = $useraccount->getRecordById($user_id);
$currentPassword = mysql_real_escape_string($_POST['currentPassword']);
$newPassword = mysql_real_escape_string($_POST['newPassword']);
if( $fetchrecord['password'] != $currentPassword){
echo "cpass";
exit;
}
if($dbObj -> dataupdate(array("password" => $newPassword) , "login_accounts" , "id" ,  $user_id )){
	unset($_SESSION['user_details']);
	unset($_SESSION['id']);
echo "pass";		
}
?>
