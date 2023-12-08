<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
//Include Lib
require_once '../lib/verifyOtp.php';
session_start();
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$phone_no = trim($_GET['phone']);
$result = array();
//print_r($_SESSION);
switch($_SESSION['USER_TYPE']){
    case 'EMPLOYEE':
        $callerPhone = $_SESSION['user_details']['phone_no'];
        break;
    case 'SUPERADMIN':
        $superAdminDtl = $dbObj->getData(array('phone_no'), 'superadmin', ' id = '. $id);
        $callerPhone = $superAdminDtl[1]['phone_no'];
        break;
}
//$callerPhone = '9718888344';

if (isset($phone_no) && !empty($phone_no)) {
   $apiList = getAvailableCallNowAPI();
   foreach ($apiList as $eachApi) {
       $result[] = callCallNowAPI($eachApi, array('receiver'=>$phone_no, 'caller' => $callerPhone));
   }
}
//print_r($result);
echo json_encode(array('success'=>true));