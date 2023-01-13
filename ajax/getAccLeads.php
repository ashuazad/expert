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
//echo json_encode($data);
$today = date('Y-m-d');
$column = array(
    "id", " CONVERT(name USING utf8) as name", " CONVERT(email USING utf8) as email",
    //"CONCAT( LEFT(TRIM(phone),3),'*****',SUBSTR(TRIM(phone),LENGTH(TRIM(phone))-1,2)) AS phone",
    "phone",
    "REPLACE(REPLACE(category,'-',' '),'+',', ') AS category",
    "IF(last_follow_up='0000-00-00 00:00:00' OR last_follow_up IS NULL, 'NONE', DATE_FORMAT(last_follow_up , ".DATE_TIME_FORMAT.")) last_follow_up",
    "IF(next_followup_date='0000-00-00 00:00:00' OR next_followup_date IS NULL, 'NONE', DATE_FORMAT(next_followup_date , ".DATE_FORMAT.")) next_followup_date",
    "CONVERT(IFNULL(message,'NONE') USING utf8) as message",
    "status", "ip", "CONVERT(address USING utf8) as address",
    "phone_location"
);
/*$column = array(
    "name","email","CONVERT(CONCAT( LEFT(TRIM(phone),3),'*****',SUBSTR(TRIM(phone),LENGTH(TRIM(phone))-1,2)) USING utf8) AS phone"
);*/
$where = " emp_id = ".$id;
#### Search ###
$search = '';
if (isset($_GET['param']) && !empty($_GET['param'])) {
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
            }
        }
    }

}
if (strlen($search)>0) {
    $search = rtrim($search, 'AND ');
    $where .= ' AND ( '.$search.' ) ';
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
        default:
            break;
    }
    //echo $searchWhere;
    if($searchWhere){
        $admsAry=$dbObj->getData($column,"leads l", $searchWhere);
    }
}
array_shift($admsAry);
//print_r($admsAry);
echo json_encode($admsAry);