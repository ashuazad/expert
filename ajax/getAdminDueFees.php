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
$id = $_SESSION['id'];
//$id = 34;
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$loadDeadAdmissions = false;
$where = ' adm.emp_id=la.id ';
$userType = null;
$posted = json_decode(file_get_contents('php://input'),1);
//echo json_encode($data);
$followUpUserSQL = "(
    CASE 
        WHEN followup_emp_id = '1' THEN 'ADMIN'
        WHEN followup_emp_id = '0' THEN (SELECT CONCAT(first_name,' ',last_name) FROM login_accounts WHERE id = emp_id)
        WHEN followup_emp_id > 1 THEN (SELECT CONCAT(first_name,' ',last_name) FROM login_accounts WHERE id = followup_emp_id)
        ELSE 1
    END) AS followup_user";
$column = array(
            "a_id", "regno", "roll_no", "adm.name", "adm.phone", "REPLACE(REPLACE(course,'-',' '),'+',', ') AS courses", "total_fee", "due_fee",
            "DATE_FORMAT( doj , ".DATE_FORMAT.") admDate",
            "DATE_FORMAT( next_due_date , ".DATE_FORMAT.") dueDate",
            "(total_fee-due_fee) AS credit_amt",
            "IF(message='',followup_remark,message) AS message",
            "IF(last_followup_date='0000-00-00 00:00:00' OR last_followup_date IS NULL, 'NONE', DATE_FORMAT(last_followup_date , ".DATE_TIME_FORMAT.")) AS last_followup_date",
             "CONCAT(la.first_name,' ',la.last_name) AS user_name",
            $followUpUserSQL
            );

$userType = (isset($_SESSION['USER_TYPE']) && !empty($_SESSION['USER_TYPE']))?$_SESSION['USER_TYPE']:null;
//Check user type and according to that applying the restrictions
switch ($userType){
    case 'SUPERADMIN':
            $loadDeadAdmissions = true;
        break;
    case 'EMPLOYEE':
            $strPermissionCondition = ''; 
            $empPermission = new userPermissions($id);
            if (strlen($empPermission->userPermission['view_emp_admissions'])>0) {
                $strPermissionCondition = "( emp_id IN ('".str_replace("','",',',$empPermission->userPermission['view_emp_admissions'])."') )";        
            }
            if (strlen($empPermission->userPermission['view_branch_admissions'])>0) {
                $strPermissionCondition += " OR (branch_name IN ('".str_replace("','",',',$empPermission->userPermission['view_branch_admissions'])."'))";
            }
            $where = " emp_id = ".$id;
            if (strlen($strPermissionCondition) > 0) {
                $where += ' AND (' + $strPermissionCondition + ')';        
            }
        break;
    default:
        $where = '1=1 ';
}

#### Filter ###
$filter = '';
if ((isset($_GET['fromDate']) && !empty($_GET['fromDate'])) && (isset($_GET['toDate']) && !empty($_GET['toDate']))) {
    $filter .= "(next_due_date>='" . $_GET['fromDate'] . "' AND next_due_date<='".$_GET['toDate']."') AND ";
}

if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $filter .= "(emp_id ='" . $_GET['empId'] . "') AND ";
}

if (isset($_GET['phone']) && !empty($_GET['phone'])) {
    $filter .= "(phone ='" . $_GET['phone'] . "') AND ";
}

if (strlen($filter)>0) {
    $filter = rtrim($filter, 'AND ');
    $where .= ' AND ( '.$filter.' ) ';
}
#### Filter ###

##### Search ######
$search = '';
if (isset($_GET['param']) && !empty($_GET['param'])) {
    foreach ($_GET as $searchKey => $searchValue) {
        if (strlen($searchValue)>0) {
            switch ($searchKey) {
                case 'roll_no':
                    $search .= "(roll_no = '" . $searchValue . "') AND ";
                    break;
                case 'name':
                    $search .= "(name LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'phone':
                    $search .= "(phone LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'admDate':
                    $search .= "(date(doj) = '" . $searchValue . "') AND ";
                    break;
                case 'dueDate':
                    $search .= "(next_due_date = '" . $searchValue . "') AND ";
                    break;
                case 'courses':
                    $search .= "(course LIKE '%" . str_replace(' ', '-', $searchValue) . "%') AND ";
                    break;
                case 'total_fee':
                    $search .= "(total_fee = '" . $searchValue . "') AND ";
                    break;
                case 'credit_amt':
                    $search .= "((total_fee-due_fee) = '" . $searchValue . "') AND ";
                    break;
                case 'due_fee':
                    $search .= "(due_fee = '" . $searchValue . "') AND ";
                    break;
                case 'message':
                    $search .= "(message LIKE '%" . $searchValue . "%') AND ";
                    break;
            }
        }
    }
}
if (strlen($search)>0) {
    $search = rtrim($search, 'AND ');
    $where .= ' AND ( '.$search.' ) ';
}
##### Search ######

$admsAry = array();
if(isset($_GET['param']) && !empty($_GET['param'])){
    $param = $_GET['param'];
    $searchWhere = false;
    switch ($param){
        case 'todaypending':
            $searchWhere = $where." AND (next_due_date = '".date('Y-m-d')."' AND due_fee > 0) order by a_id desc";
            break;
        case 'allpending':
            $searchWhere = $where." AND (next_due_date <= '".date('Y-m-d')."' AND due_fee > 0) order by a_id desc";
            break;
        case 'todaydone':
           // $column[] = "DATE_FORMAT((SELECT followup FROM admission_followups WHERE regno = adm.regno ORDER BY followup DESC limit 0,1),".DATE_TIME_FORMAT.") AS last_followup_date";
            $todaydoneWhere = "(SELECT regno FROM admission_followups WHERE date(followup) = '".date('Y-m-d')."')";
            $searchWhere = $where . " AND regno IN(".$todaydoneWhere.") order by last_followup_date desc";
            break;
        case 'allbooking':
            $searchWhere = $where." AND ((total_fee-due_fee) < 2000) order by a_id desc";
            break;
        default:
            break;
    }
    if ($param != 'todaydone') {
        $column[] = "IFNULL(DATE_FORMAT((SELECT recipt_date FROM fee_detail WHERE reg_no = adm.regno ORDER BY recipt_date DESC limit 0,1), ".DATE_TIME_FORMAT."),'NONE') AS last_fees_date";
    }
    //echo $searchWhere;
    if($searchWhere){
        $admsAry=$dbObj->getData($column,"admission adm, login_accounts la", $searchWhere);
    }
}
array_shift($admsAry);
echo json_encode($admsAry);