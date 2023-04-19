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

$errors = array();
$result = array();
$result['success'] = false;
$result['errors'] = $errors;
$postedData = json_decode(file_get_contents('php://input'), true);
$apiClass = array('WHATSUP','CALL','SMS');
//$type = 'LOGIN_OTP';
$smsTypes = array(
    'due' => array(
          'DUE_FEES_3_DAY_BEFORE' => '3 Day Before', 
          'DUE_FEES_ON_DAY' => 'On Due Day', 
          'DUE_FEES_1_DAY_AFTER' => 'After Due Day'
       ),
    'booking' => array()   
    );
if (!isset($postedData['sms_type'])) {
    $errors[] = 'Page SMS Type is missing';
}
if (!isset($postedData['type'])) {
    $errors[] = 'SMS Type is missing';
}
if(!array_key_exists($postedData['type'], $smsTypes[$postedData['sms_type']])) {
    $errors[] = 'Invalid SMS Type';
}

if (count($errors) == 0) {
    $title = trim($postedData['title']);
    $defaultValue = ($postedData['isDefault']==true)?'1':'0';
    $content = $postedData['content'];
    $type = $postedData['type'];
$query = <<<SQL
            INSERT INTO  
                sms (`sms_title`,`sms_content`,`default_sms`,`type`) 
            VALUES
                ('{$title}',
                '{$content}',
                '{$defaultValue}',
                '{$type}') 
            ON DUPLICATE KEY UPDATE 
                `sms_title` = '{$title}', 
                `sms_content` = '{$content}', 
                `default_sms` = '{$defaultValue}', 
                `type` = '{$type}';
SQL;

    if (mysql_query($query)) {
        $result['success'] = true;
    }

    if ($postedData['isDefault']) {
        $updateSql = "UPDATE sms SET default_sms=0 WHERE type = '".$postedData['type']."' AND TRIM(sms_title) != '" . trim($postedData['title']) . "'";
        mysql_query($updateSql);
    }
    
}
$result['errors'] = $errors;
echo json_encode($result);