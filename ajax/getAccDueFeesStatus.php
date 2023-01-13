<?php
date_default_timezone_set("Asia/Kolkata");
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
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$loadDeadAdmissions = false;
$where = '1=1 ';
$userType = null;

$userType = (isset($_SESSION['USER_TYPE']) && !empty($_SESSION['USER_TYPE']))?$_SESSION['USER_TYPE']:null;
//var_dump($userType);
//Check user type and according to that applying the restrictions
switch ($userType){
    case 'SUPERADMIN':
        $loadDeadAdmissions = true;
        $todaydoneWhere = "(SELECT regno FROM admission_followups WHERE date(followup) = '".date('Y-m-d')."')";
        break;
    case 'EMPLOYEE':
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
        $todaydoneWhere = "(SELECT regno FROM admission_followups WHERE user_id = ".$id." AND date(followup) = '".date('Y-m-d')."')";
        break;
    default:
        $where = '1=1 ';
}

if (!$loadDeadAdmissions) {
    $where .= " AND fee_status NOT IN('Dead') ";
}

$query = "SELECT 
            (SELECT count(*) FROM admission WHERE ".$where." AND (next_due_date = '".date('Y-m-d')."' AND due_fee > 0)) AS TODAY_PENDING,
            (SELECT count(*) FROM admission WHERE ".$where." AND (next_due_date <= '".date('Y-m-d')."' AND due_fee > 0)) AS ALL_PENDING,
            (SELECT count(*) FROM admission WHERE regno IN(".$todaydoneWhere.")) AS TODAY_DONE,
            (SELECT count(*) FROM admission WHERE ".$where." AND ((total_fee-due_fee) < 2000)) AS ALL_BOOKING
          FROM  
            DUAL";
echo json_encode(mysql_fetch_assoc(mysql_query($query)));