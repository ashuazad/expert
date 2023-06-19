<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
require_once '../includes/communication.php';
require_once '../includes/functions.php';
require_once '../includes/admission.php';
session_start();
$stdRegno = '';
if (isset($_SESSION['Zend_Auth']) && !empty($_SESSION['Zend_Auth'])) {
    $stdRegno = $_SESSION['Zend_Auth']['storage']->regno;
}
if(empty($_SESSION['id']) && empty($stdRegno)){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$studentObj = new student();
$commObj = new communication();
$admission = new admission();
$today=date('ymd');

$errors = array();
$result = array();
$result['success'] = false;
$postedData = json_decode(file_get_contents('php://input'), true);
$searchData = array(
                    'regno'=>$postedData['regno'],
                    'phoneNo'=>$postedData['phoneNo'],
                    'rollNo'=>$postedData['rollNo'],
                    );
$regDetails = $admission->getRegistrationDetails($searchData);
if ($regDetails) {
    // Check generate certificate conditions
    
    $result['success'] = true;
    $result['data'] = $regDetails;
    $nofDays = 0;
    $isIncompleteFees = false;
    $dayCompleted = false;
    if ($regDetails['bill_pending'] == '1') {
        $isIncompleteFees = true;
        $result['success'] = false;
        $result['data'] = array();
        $errors[] = 'Due to incomplete fees Certificate is not ready';
    } else {
        $reciptDetails =  $dbObj->getData(array('recipt_date'),'fee_detail',"reg_no ='".$regDetails['regno']."' order by f_id ASC");
        
        if ($reciptDetails[0]>1) {
            array_shift($reciptDetails);
            $lastReceiptDate = date_create($regDetails['last_receipt_date']);
            $admissionReceiptDate = date_create($reciptDetails[1]['recipt_date']);
            $intervalBtwRecepit = date_diff($lastReceiptDate, $admissionReceiptDate);
            $nofDaysDiff = $intervalBtwRecepit->format('%a');
            if ($nofDaysDiff > 30) {
                $nofDays = 10;
            } else {
                $nofDays = 30;
            }
            $result['data']['issue_date'] = date('Y-m-d', strtotime($lastReceiptDate->format('Y-m-d'). ' + '.$nofDays.' days'));
        } else if ($reciptDetails[0]==1) {
            $nofDays = 30;
            array_shift($reciptDetails);
            $lastReceiptDate = date_create($reciptDetails[0]['recipt_date']);
            $result['data']['issue_date'] = date('Y-m-d', strtotime($lastReceiptDate->format('Y-m-d'). ' + '.$nofDays.' days'));
        }
        
    }
    if ($isIncompleteFees) {
        $result['success'] = false;
        $result['data'] = array();
    } else {
       $dateDiff = date_diff(date_create(date('Y-m-d')), $lastReceiptDate);
       $currentDateDiff = $dateDiff->format('%a');
       if ($currentDateDiff > $nofDays) {
            $result['success'] = true;
       } else {
        $result['success'] = false;
        $result['data'] = array();
        $errors[] = 'Your Certificate will generate after ' . date('d-M-YCC', strtotime($lastReceiptDate->format('Y-m-d'). ' + '.$nofDays.' days'));
       }
    }
} else {
    $errors[] = 'Not Found';
}

$result['errors'] = $errors;
echo json_encode($result);