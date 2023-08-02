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
$posted = json_decode(file_get_contents('php://input'),1);
$isPermissionEnable = false;
//echo json_encode($data);
$column = array(
            "a_id", "regno", "roll_no", "name", "REPLACE(REPLACE(course,'-',' '),'+',', ') AS courses", "total_fee", "due_fee", 
            "DATE_FORMAT( doj , '%d-%m-%y') admDate",
            "DATE_FORMAT( next_due_date , '%d-%c-%y') dueDate",
            "(total_fee-due_fee) AS credit_amt",
            "IF(message='',followup_remark,message) AS message",
            "phone AS phone_full",
            ' INSERT(phone, 4, 4, "****") AS phone'
            );

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

if ($isPermissionEnable) {
    $column[] = '(SELECT branch_name FROM login_accounts WHERE id = (SELECT branch_id FROM login_accounts WHERE id = emp_id)) AS branch_name';
    $column[] = '(SELECT CONCAT(first_name,last_name) FROM login_accounts WHERE id = emp_id) AS emp_name';
}

$nofr=$posted['pageInfo']['nofpg'];
$pg=$posted['pageInfo']['page'];
$startLmt=($pg-1)*$nofr;

#### Search ###
$serachFlag = false;
if(isset($posted['params'])){
    $serachQuery = '(';
    foreach($posted['params'] as $srchKey=>$srchVal){
        if (strlen($srchVal)>0 || is_array($srchVal)) {
            $serachFlag = true;
            switch($srchKey) {
                case 'amount':
                        $serachQuery .= " credit_amt = '".$srchVal."' AND";                
                    break;
                case 'phone':
                        $serachQuery .= " phone like '%".$srchVal."%' AND";
                    break;   
                case 'course':
                        $serachQuery .= " course like '%".$srchVal."%' AND";
                    break;       
                case 'date':
                    if ((strlen($srchVal['fromDate'])>0) && (strlen($srchVal['toDate'])>0)) {
                        $serachQuery .= "(doj>='".$srchVal['fromDate']."' AND doj<='".$srchVal['toDate']."') AND";    
                    } else {
                        $serachFlag = false;                        
                    }
                    break;
                case 'fee_status':
                    $serachQuery .= " fee_status = '".$srchVal."' AND";
                    break;    
            }    
        }
    }
    
    $serachQuery = rtrim($serachQuery,"AND") . ' ) ';
}

if ($serachFlag) {
        $where .= ' AND ' . $serachQuery;     
    }
#### Search ###
$norAmd =$dbObj->getData(array('count(*) AS nor'),"admission", $where);
$admsAry=$dbObj->getData($column,"admission", $where." order by a_id desc limit $startLmt,$nofr");
array_shift($admsAry);
$arr_response = array(
                        'nofrows' => $norAmd[1]['nor'],
                        'rows' => $admsAry
                    );
echo json_encode($arr_response);