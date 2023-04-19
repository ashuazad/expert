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
$types = "''";
$smsTypes = array(
               'due' => array(
                     'DUE_FEES_3_DAY_BEFORE' => '3 Day Before', 
                     'DUE_FEES_ON_DAY' => 'On Due Day', 
                     'DUE_FEES_1_DAY_AFTER' => 'After Due Day'
                  ),
               'booking' => array()   
               );
if (isset($_GET['type']) && !empty($_GET['type'])) {
   $requestType = trim($_GET['type']);
   $types = "'".implode("','",array_keys($smsTypes[$requestType]))."'";                                 
}
if (isset($_GET['coType']) && !empty($_GET['coType'])) {
   $requestcoType = trim($_GET['coType']);
   $types = "'".$requestcoType."'";                                 
}
$strSqlTypeCol = "CASE 
                     WHEN type = 'DUE_FEES_3_DAY_BEFORE' THEN '" . $smsTypes[$requestType]['DUE_FEES_3_DAY_BEFORE'] . "'". 
                     "WHEN type = 'DUE_FEES_ON_DAY' THEN '" . $smsTypes[$requestType]['DUE_FEES_ON_DAY'] . "'".
                     "WHEN type = 'DUE_FEES_1_DAY_AFTER' THEN '" . $smsTypes[$requestType]['DUE_FEES_1_DAY_AFTER'] . "'".
                  "ELSE 'NONE'".
                  "END AS type_text";


$columnList = array('sms_title','sms_content','type', $strSqlTypeCol, 'default_sms');
$smsList = $dbObj -> getData($columnList, 'sms' , 'type IN('.$types.')');
array_shift($smsList);
$returnData = [];
foreach($smsList as $each) {
   $each['sms_content'] = urldecode($each['sms_content']);
   $returnData[] = $each;
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($returnData);