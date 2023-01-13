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
    'f_id',
    "DATE_FORMAT(recipt_date, ".DATE_TIME_FORMAT.") AS recipt_date",
    'amt',
    'payment_mode',
    "IF(cheque_no='','None',cheque_no) AS cheque",
    "CONCAT(first_name, ' ', last_name) AS user"
);
$postData = json_decode(file_get_contents("php://input"),true);
$data = $dbObj->getData($columns, 'fee_detail as FD, login_accounts LG', ' FD.emp_id = LG.id AND a_id = ' . $postData['a_id'] . ' ORDER BY FD.recipt_date DESC');
array_shift($data);
echo json_encode($data);