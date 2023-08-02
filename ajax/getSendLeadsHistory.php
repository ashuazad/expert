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
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$studentObj = new student();
$today=date('ymd');
$columns = array(
    'object_id',
    "DATE_FORMAT(frwDate, ".DATE_TIME_FORMAT.") AS send_date_time",
    " (CASE
        WHEN object_type = 'LEAD' THEN (SELECT name FROM leads WHERE id = object_id)
        WHEN object_type = 'ADMISSION' THEN (SELECT name FROM admission WHERE a_id = object_id)
        ELSE 'NONE'
     END) AS name",
     " (CASE
        WHEN object_type = 'LEAD' THEN (SELECT phone FROM leads WHERE id = object_id)
        WHEN object_type = 'ADMISSION' THEN (SELECT phone FROM admission WHERE a_id = object_id)
        ELSE 'NONE'
     END) AS phone",
    'object_type',
    '(SELECT first_name FROM login_accounts WHERE id=modified_by) AS send_by',
    "(SELECT first_name FROM login_accounts WHERE id=currentId) AS last_username",
    "(SELECT first_name FROM login_accounts WHERE id=nextId) AS next_username"
);
$postData = json_decode(file_get_contents("php://input"),true);
$where = '';
if (isset($_GET['fromDate']) && !empty($_GET['fromDate']) && isset($_GET['toDate']) && !empty($_GET['toDate'])) {
    $where .= " (frwDate  >= '".$_GET['fromDate']." 00:00:00' AND frwDate <= '".$_GET['toDate']." 23:59:59') AND ";    
}

if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $where .= " modified_by = ".$_GET['empId']." AND ";
}

$where = (strlen($where)) ? rtrim($where, 'AND '):'1=1';

$data = $dbObj->getData($columns, 'leadfrwdhistory', $where.' order by frwDate DESC');
array_shift($data);
echo json_encode($data);