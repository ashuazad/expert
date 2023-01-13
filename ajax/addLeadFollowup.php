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
$insertData = array();
$errors = array();
$postData = json_decode(file_get_contents("php://input"),true);
$messageErrorFlag = 0;
/*if (empty($postData['remark']) && !isset($postData['remark'])  || (strlen($postData['remark']) == 0)) {
    $messageErrorFlag++;    
}*/
if (empty($postData['message']) && !isset($postData['message'])  || (strlen($postData['message']) == 0)) {
    $messageErrorFlag++;
}

if($messageErrorFlag>1){
    $errors[] = "Message Or Remark is empty";    
}

if (empty($postData['next_followup_date']) && !isset($postData['next_followup_date']) || (strlen($postData['next_followup_date']) == 0)) {
    $errors[] = "Next Followup Date is Empty";
}
if (empty($postData['status']) && !isset($postData['status']) || (strlen($postData['status']) == 0)) {
    $errors[] = "Status is Empty";
}
$leadData = $dbObj->getData(array("id"),'leads', " id = '" . $postData['id'] . "'");
if ($leadData[0] == 0) {
    $errors[] = "Lead Not Found";
}
$insertData['status'] = $postData['status'];
$insertData['emp_id'] = $id;
$insertData['followup_date'] = date('Y-m-d H:i:s');
//$insertData['remark'] = $postData['remark'];
$insertData['message'] = $postData['message'];
$insertData['next_followup_date'] = $postData['next_followup_date'];
$insertData['lead_id'] = $leadData[1]['id'];
$insertData['pid'] = $postData['pid'];
if(count($errors)>0){
    $response = array("success"=>false, "errors"=>$errors);
} else {
    $dbObj->dataInsert($insertData, "user_query");
    $admUpdate = array(
                'message' => $insertData['message'],
                'next_followup_date' => $insertData['next_followup_date'],
                'status' => $postData['status'],
                'last_follow_up' => date('Y-m-d H:i:s')
                );
    $dbObj->dataUpdate($admUpdate, "leads", "id", $postData['id']) ;
    $response = array("success"=>true, "id"=>$leadData[1]['id'],"errors"=>$errors);
}
echo json_encode($response);