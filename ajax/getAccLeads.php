<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
require_once '../v11/lib/utilities.php';
require_once '../includes/userPermissions.php';
require_once '../includes/functions.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
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
//echo json_encode($data);
$today = date('Y-m-d');
$column = array(
    "id", " CONVERT(name USING utf8) as name", " CONVERT(email USING utf8) as email",
    "REPLACE(REPLACE(category,'-',' '),'+',', ') AS category",
    "IF(last_follow_up='0000-00-00 00:00:00' OR last_follow_up IS NULL, 'NONE', DATE_FORMAT(last_follow_up , ".DATE_TIME_FORMAT.")) last_follow_up",
    "IF(next_followup_date='0000-00-00 00:00:00' OR next_followup_date IS NULL, 'NONE', DATE_FORMAT(next_followup_date , ".DATE_FORMAT.")) next_followup_date",
    "CONVERT(IFNULL(message,'NONE') USING utf8) as message",
    "status", "ip", "CONVERT(address USING utf8) as address",
    "phone_location",
    "phone AS phone_full"
);
/*$column = array(
    "name","email","CONVERT(CONCAT( LEFT(TRIM(phone),3),'*****',SUBSTR(TRIM(phone),LENGTH(TRIM(phone))-1,2)) USING utf8) AS phone"
);*/
$isPermissionEnable = false;
#### Search ###
$where = " emp_id = ".$id;
$search = '';
if (isset($_GET['param']) && !empty($_GET['param'])) {
    // Incase of param all checking user type and permission.
    if ($_GET['param'] == 'all') {
        switch ($_SESSION['USER_TYPE']) {
            case 'SUPERADMIN' :
                    $where = ""; 
                    $isPermissionEnable = true;
                break;
            case 'EMPLOYEE':
                    $empPermission = new userPermissions($id);
                    // Check search lead and admission permission
                    if ($empPermission->userPermission['search_leads_admissions']) {
                        $where = "";
                        $isPermissionEnable = true;
                    }
                    // Check send lead and admission permission
                    if ($empPermission->userPermission['send_leads_admissions']) {
                        $where = "";
                        $isPermissionEnable = true;
                    }
                break;
        }
    }

    foreach ($_GET as $searchKey => $searchValue) {
        if (strlen($searchValue)>0) {
            switch ($searchKey) {
                case 'name':
                    $search .= "(name LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'phone':
                    $search .= "(phone LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'category':
                    $search .= "(category LIKE '%" . str_replace(' ', '-', $searchValue) . "%') AND ";
                    break;
                case 'message':
                    $search .= "(message LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'status':
                    $search .= "(status LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'branch':
                    $search .= "(branch_id = '" . $searchValue . "') AND ";
                    break;
                case 'emp':
                    $search .= "(branch_id = '" . $_GET['branch'] . "' AND emp_id = '" . $searchValue . "') AND ";
                    break;
            }
        }
    }

    if (isset($_GET['from_date']) && !empty($_GET['from_date']) && isset($_GET['to_date']) && !empty($_GET['to_date'])) {
        $search.=" (create_date  >= '".$_GET['from_date']." 00:00:00' AND create_date <= '".$_GET['to_date']." 23:59:59') AND ";    
    }

}
if (strlen($search)>0) {
    $search = rtrim($search, 'AND ');
    $where .= (strlen($where)) ? ' AND ( '.$search.' ) ' : ' ( '.$search.' ) ';
}

#### Search ###
$admsAry = array();
if(isset($_GET['param']) && !empty($_GET['param'])){
    $param = $_GET['param'];
    $searchWhere = false;
    switch ($param){
        case 'todaypending':
            $searchWhere = $where." AND ((status not in('Start','Complete','Dead')  and date(next_followup_date)='".$today."' and emp_id=".$id." ) 
          OR (DATE(assingment_data) = '".$today."' AND Message IS NULL AND emp_id=".$id." )) order by l.next_followup_date desc, l.hits desc";
            break;
        case 'allpending':
            $searchWhere = $where." AND ((status not in('Start','Complete','Dead')  and date(next_followup_date)<='".$today."' and emp_id=".$id." ) 
          OR (DATE(assingment_data) <= '".$today."' AND Message IS NULL AND emp_id=".$id.")) order by l.next_followup_date desc, l.hits desc";
            break;
        case 'todaynew':
            $searchWhere = " emp_id = ".$id." AND (frwId > 0 AND message IS NULL) AND DATE(assingment_data) ='".$today."'";
            break;
        case 'todaydone':
            $column[] = "DATE_FORMAT((SELECT followup_date FROM user_query WHERE lead_id = l.id ORDER BY followup_date DESC limit 0,1),".DATE_TIME_FORMAT.") AS last_followup_date";
            $todaydoneWhere = "(SELECT lead_id FROM user_query WHERE emp_id = ".$id." AND date(followup_date) = '".date('Y-m-d')."')";
            $searchWhere = "id IN(".$todaydoneWhere.") order by last_follow_up desc";
            break;
        case 'allStatus':
            $searchWhere = $where." AND (status != 'Start') order by last_follow_up desc";
            break;
        case 'all':
            $searchWhere = $where." order by create_date desc";
            $column[] = "(SELECT count(status) noStatus FROM user_query uq WHERE uq.lead_id = id and uq.status = 'Dead') AS NO_DEAD";
            $column[] = 'DATE_FORMAT(create_date , '.DATE_TIME_FORMAT.') created_date';    
            $column[] = 'hits';
            $column[] = 'r_status';
            $column[] = 'emp_id';
            break;    
        default:
            break;
    }
    if ($isPermissionEnable) {
        $column[] = '(SELECT first_name FROM login_accounts WHERE id = emp_id) AS lead_emp_name';    
    }
    $column[] = ($param == 'all')?' phone':' INSERT(phone, 4, 4, "****") AS phone';
    //echo $searchWhere;
    if($searchWhere){
        $admsAry=$dbObj->getData($column,"leads l", $searchWhere);
    }
}
array_shift($admsAry);
//print_r($admsAry);
echo json_encode($admsAry);
switch (json_last_error()) {
    case JSON_ERROR_NONE:
        //echo ' - No errors';
    break;
    case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
    break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
    break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
    break;
    case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON';
    break;
    case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
    break;
    default:
        echo ' - Unknown error';
    break;
    }