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

if (empty($postData['next_followup']) && !isset($postData['next_followup']) || (strlen($postData['next_followup']) == 0)) {
    $errors[] = "Next Followup Date is Empty";
}
if (empty($postData['fee_status']) && !isset($postData['fee_status']) || (strlen($postData['fee_status']) == 0)) {
    $errors[] = "Status is Empty";
}
$amdData = $dbObj->getData(array("regno"),'admission', " a_id = '" . $postData['a_id'] . "'");
if ($amdData[0] == 0) {
    $errors[] = "Admission Not Found";
}
$insertData['fee_status'] = $postData['fee_status'];
$insertData['user_id'] = $id;
$insertData['followup'] = date('Y-m-d H:i:s');
//$insertData['remark'] = $postData['remark'];
$insertData['message'] = $postData['message'];
$insertData['next_followup'] = $postData['next_followup'];
$insertData['regno'] = $amdData[1]['regno'];
if(count($errors)>0){
    $response = array("success"=>false, "errors"=>$errors);
} else {
    $dbObj->dataInsert($insertData, "admission_followups");
    $admUpdate = array(
                'message' => $insertData['message'],
                'next_due_date' => $insertData['next_followup'],
                'fee_status' => $postData['fee_status'],
                'followup_remark' => $postData['remark'],
                'followup_emp_id' => $id,
                'last_followup_date' => $insertData['followup']
                );
    $dbObj->dataUpdate($admUpdate, "admission", "a_id", $postData['a_id']) ;    
    $response = array("success"=>true, "regno"=>$amdData[1]['regno'],"errors"=>$errors);
}
echo json_encode($response);