<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../v11/lib/utilities.php';
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

$sql =
    "SELECT	
	SUM(
		IF(a.insentive_status='',
			IF(a.insentive_amt='',
					ROUND((a.total_fee*l.insentive/100), 2),
					a.insentive_amt
			),
			0
		)
	) AS PENDING,
    SUM(
		IF(a.insentive_status!='',
			IF(a.insentive_amt='',
					ROUND((a.total_fee*l.insentive/100), 2),
					a.insentive_amt
			),
			0
		)
	) AS APPROVED
    
FROM 
	admission a,
    login_accounts l 
WHERE 
    a.emp_id=l.id AND    
    a.due_fee = 0 AND
    MONTH(a.last_receipt_date) = MONTH(CURRENT_DATE()) AND 
    YEAR(a.last_receipt_date) = YEAR(CURRENT_DATE()) AND
    emp_id = $id 
";
//echo $sql;
$rows = array();
$res  = mysql_query($sql);
echo mysql_error();
$eachRow = mysql_fetch_assoc($res);
echo json_encode($rows);
