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
$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;
//print_r($_POST);
//echo $startLmt;
$sql = "SELECT 
	l.first_name AS user,
	DATE_FORMAT(a.last_receipt_date, '%d-%m-%y %H:%i:%s %r') AS recipt_date,
	a.roll_no,
	a.name,
	DATE_FORMAT(a.doj, '%d-%m-%y') AS doj,
	a.a_id,
	a.total_fee,
	a.insentive_amt,
	IF(a.insentive_amt='',ROUND((a.total_fee*l.insentive/100), 2),a.insentive_amt) AS insentive_cal,
	IF(a.insentive_date = '0000-00-00','None',DATE_FORMAT(a.insentive_date, '%d-%m-%y')) AS insentive_date,
	IF(a.insentive_status='','Pending','Approved') AS insentive_status  
FROM 
	admission a, 
	login_accounts l 
WHERE 
    a.emp_id=l.id               AND
    a.due_fee = 0     
";
//Add Where from post
if(strlen($_POST['whereCnd'])){
    $sql .= ' AND ' . $_POST['whereCnd'];
}
//Order by
$sql .= ' ORDER BY a.last_receipt_date DESC';
// Limit
$limit = ' limit ';
if ($_POST['pageN']) {
    $startLmt = ($_POST['pageN']-1)*$_POST['nfrPP'];
    $limit .= $startLmt . ',' . $_POST['nfrPP'];
    $sql .= $limit;
}
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
