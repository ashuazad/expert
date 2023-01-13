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
                "regno",
                "message",
                "remark",
                "DATE_FORMAT(followup, ".DATE_TIME_FORMAT.") AS followup",
                "DATE_FORMAT(next_followup, ".DATE_FORMAT.") AS next_followup",
                "CONCAT(LA.first_name,' ',LA.last_name) AS user",
                "fee_status AS status"
                );
$regno = '';
$data = json_decode(file_get_contents('php://input'),1);
if(!empty($data['regno']) && isset($data['regno']) || (strlen($data['regno'])>0))
    $regno = $data['regno'];
    
$norows = $_GET['r'];
$sqlLimit = (isset($norows) && !empty($norows))?' Limit 0, '.$norows :'';

$resultData = $dbObj->getData($columns,'admission_followups AF, login_accounts LA'," (AF.user_id = LA.id) AND regno = '" . $regno . "' ORDER BY followup DESC " . $sqlLimit);
array_shift($resultData);
echo json_encode($resultData);