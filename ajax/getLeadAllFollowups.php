<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
require_once '../v11/lib/utilities.php';
 session_start();
if(empty($_SESSION['id'])){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
//$id = 34;
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$studentObj = new student();
$today=date('ymd');
$columns = array(
                "AF.id",
                "message",
                "DATE_FORMAT(followup_date, ".DATE_TIME_FORMAT.") AS followup",
                "IF(next_followup_date='0000-00-00 00:00:00' OR next_followup_date IS NULL, 'NONE', DATE_FORMAT(next_followup_date , ".DATE_FORMAT.")) AS next_followup",
                "CONCAT(LA.first_name,' ',LA.last_name) AS user",
                "AF.status AS status"
                );
$leadId = '';
$data = json_decode(file_get_contents('php://input'),1);
if(!empty($data['leadId']) && isset($data['leadId']) || (strlen($data['leadId'])>0))
    $leadId = $data['leadId'];
    
$norows = $_GET['r'];
$sqlLimit = (isset($norows) && !empty($norows))?' Limit 0, '.$norows :'';

$resultData = $dbObj->getData($columns,'user_query AF, login_accounts LA'," (AF.emp_id = LA.id) AND AF.lead_id = '" . $leadId . "' ORDER BY followup_date ASC ");
array_shift($resultData);
echo json_encode($resultData);