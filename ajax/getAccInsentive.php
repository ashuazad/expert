<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
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
$sql = "SELECT 
	DATE_FORMAT(a.last_receipt_date, '%d-%m-%y %H:%i:%s %r') AS recipt_date,
	a.roll_no,
	a.name,
	DATE_FORMAT(a.doj, '%d-%m-%y') AS doj,
	a.insentive_amt,
	IF(a.insentive_date = '0000-00-00','None',DATE_FORMAT(a.insentive_date, '%d-%m-%y')) AS insentive_date,
	IF(a.insentive_status='','Pending','Approved') AS insentive_status  
FROM 
	admission a 
WHERE 
    a.due_fee = 0 AND 
    MONTH(a.last_receipt_date) = MONTH(CURRENT_DATE()) AND YEAR(a.last_receipt_date) = YEAR(CURRENT_DATE()) AND
    emp_id = $id 
";

//Order by
$sql .= ' ORDER BY a.last_receipt_date DESC';

//echo $sql;
$rows = array();
$res  = mysql_query($sql);
echo mysql_error();
$rows['nor'] = mysql_num_rows($res);
while ($eachRow = mysql_fetch_assoc($res)) {
    $rows['rows'][] = $eachRow;
}
echo json_encode($rows);
?>
