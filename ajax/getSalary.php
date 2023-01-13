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
$currentMonth = date('Y-m');
$emp_id = $_SESSION['id'];
//Incentive cal
$sqlInsentive_cal = "SELECT 
                        TRUNCATE(SUM(IF(a.insentive_amt=0,ROUND((a.total_fee*l.insentive/100), 2),a.insentive_amt)),0) AS insentive_cal
                    FROM 
                        admission a, 
                        login_accounts l 
                    WHERE 
                        a.emp_id=l.id AND 
                        a.due_fee = 0 AND 
                        (DATE_FORMAT(a.last_receipt_date,'%Y-%m') = '" . $currentMonth . "') AND  
                        a.emp_id = " . $emp_id;
$result_sql_Insentive_cal = mysql_query($sqlInsentive_cal);
$row_sql_Insentive_cal = mysql_fetch_assoc($result_sql_Insentive_cal);
$record['incentive'] = empty($row_sql_Insentive_cal['insentive_cal']) ? '0' : $row_sql_Insentive_cal['insentive_cal'];
//Salary Status
$sqlHistory = "SELECT * FROM emp_payment_history WHERE emp_id = " . $emp_id . " AND DATE_FORMAT(payment_month,'%Y-%m') = '" . $currentMonth . "'";
$resultHistory = mysql_query($sqlHistory);

if (mysql_num_rows($resultHistory) > 0 ) {
    $rowHistory = mysql_fetch_assoc($resultHistory);
  //  $record['incentive'] = $rowHistory['insentive'];
    $record['salary'] = $rowHistory['salary'];
    $record['target'] = $rowHistory['target'];
    //$record['totalSalary'] = $rowHistory['total_salary'];
} else {
//Incentive cal
// Salary
    $sqlEmpDtl = "SELECT
                l.salary, 
                '0' AS target
              FROM 
                login_accounts AS l 
              WHERE 
                id = " . $emp_id;
    $resultEmpDtl = mysql_query($sqlEmpDtl);
    $rowEmpDtl = mysql_fetch_assoc($resultEmpDtl);
    $record['salary'] = $rowEmpDtl['salary'];
    $record['target'] = $rowEmpDtl['target'];
}
$record['totalSalary'] = $record['salary'] + $record['target'] + $record['incentive'];
// Salary
echo json_encode($record);
?>
