<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
require_once '../includes/userPermissions.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
//$id = 34;
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$loadDeadAdmissions = false;

$strPermissionCondition = ''; 
$empPermission = new userPermissions($id);
if (strlen($empPermission->userPermission['view_emp_admissions'])>0) {
    $isPermissionEnable = true;
    $strPermissionCondition = "( emp_id IN ('".str_replace(',',"','",$empPermission->userPermission['view_emp_admissions'])."') )";
}
if (strlen($empPermission->userPermission['view_branch_admissions'])>0) {
    $isPermissionEnable = true;
    $strPermissionCondition .= " (branch_name IN ('".str_replace("','",',',$empPermission->userPermission['view_branch_admissions'])."'))";
}
$where = " emp_id = ".$id;

if (strlen($strPermissionCondition) > 0) {
    $where .= ' OR (' . $strPermissionCondition . ')';        
}
// All Admission includes Dead as well
$allAmdWhere = $where;
if (!$loadDeadAdmissions) {
    $where .= " AND fee_status NOT IN('Dead') ";
}

mysql_query('SET time_zone = "+05:30"');
$table_adm = 'admission';
$today_pending = $dbObj->getData(array('count(*) AS `count`'), $table_adm, $where . " AND (next_due_date = '".date('Y-m-d')."' AND due_fee > 0)");
$all_pending = $dbObj->getData(array('count(*) AS `count`'), $table_adm, $where . " AND (next_due_date <= '".date('Y-m-d')."' AND due_fee > 0)");
$all_booking = $dbObj->getData(array('count(*) AS `count`'), $table_adm, $where . " AND (due_fee > 0 AND ((total_fee-due_fee) < 1000))");
$all_admission = $dbObj->getData(array('count(*) AS `count`'), $table_adm, $allAmdWhere );

$returnArray = array(
    "today_pending"=>array(
            "value"=>$today_pending[1]['count'],
            "max" => $all_pending[1]['count']
            ),
    "all_pending"=>array(
        "value"=>$all_pending[1]['count'],
        "max" => $all_admission[1]['count']
    ),
    "all_booking"=>array(
        "value"=>$all_booking[1]['count'],
        "max" => $all_admission[1]['count']
    ),
    "all_admission"=>array(
        "value"=>$all_admission[1]['count'],
        "max" => $all_admission[1]['count']
    )
);
//print_r($returnArray);
echo json_encode($returnArray);