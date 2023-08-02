<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
require_once '../v11/lib/utilities.php';
require_once '../includes/userPermissions.php';
session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
if ($_SESSION['user_permission']['search_leads_admissions']!='1' && ($_SESSION['USER_TYPE']=='EMPLOYEE')) {
    header('Location: https://www.advanceinstitute.co.in'.'/account/index.php');
    exit;
}
$id = $_SESSION['id'];
//print_r($_SESSION);
//$id = 34;
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$posted = json_decode(file_get_contents('php://input'),1);
$posted_emp_id = $posted['emp_id'];
$select = explode(',',rtrim($posted['select'],', '));
$updateCol = array(
                    'emp_id'=>$posted_emp_id,
                    'lead_userId'=>$posted_emp_id,
                    'message' => '',
                    'followup_remark' => ''
                );
foreach ($select as $eachAdmId) {
    $currentDetails = $dbObj->getData(array('emp_id'),'admission',"a_id=$eachAdmId");
    array_shift($currentDetails);
    $arr_data = array(
        'object_id' => $eachAdmId,
        'object_type' => 'ADMISSION',
        'currentId' => $currentDetails[0]['emp_id'],
        'nextId' => $posted_emp_id,
        'frwDate' => date('Y-m-d H:i:s'),
        'modified_by' => $id
    );
    $dbObj->dataInsert($arr_data, 'leadfrwdhistory');
   // mysql_query("insert into leadfrwdhistory values( NULL , ".$select[$i]." ,'LEAD',".$dataEmpId['emp_id']." , $emp , '$dateSendLd', $id )");
    $dbObj->dataupdate($updateCol, 'admission', 'a_id', $eachAdmId);
}
echo json_encode(array('success'=>true));
//echo json_encode(mb_convert_encoding($admsAry, "UTF-8", "UTF-8"));