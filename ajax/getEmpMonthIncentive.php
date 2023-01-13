<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
session_start();
if(!$_SESSION['id']){
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$record = array();
$_POST = json_decode(file_get_contents("php://input"),true);
$month = date('Y-m',strtotime($_POST['month']));
$emp_id = $_POST['emp_id'];
$sqlInsentive_cal = "SELECT 
                        TRUNCATE(SUM(IF(a.insentive_amt='',ROUND((a.total_fee*l.insentive/100), 2),a.insentive_amt)),0) AS insentive_cal 
                    FROM 
                        admission a, 
                        login_accounts l 
                    WHERE 
                        a.emp_id=l.id AND 
                        a.due_fee = 0 AND 
                        (DATE_FORMAT(a.last_receipt_date,'%Y-%m') = '" . $month . "') AND  
                        a.emp_id = " . $emp_id;
$result_sqlInsentive_cal = mysql_query($sqlInsentive_cal);
$row_sqlInsentive_cal = mysql_fetch_assoc($result_sqlInsentive_cal);
$record['incentive'] = empty($row_sqlInsentive_cal['insentive_cal']) ? '0' : $row_sqlInsentive_cal['insentive_cal'];
echo json_encode($record);

