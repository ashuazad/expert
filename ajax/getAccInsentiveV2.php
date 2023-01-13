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
$search = '';
if (isset($_GET['param']) && !empty($_GET['param'])) {
    foreach ($_GET as $searchKey => $searchValue) {
        if (strlen($searchValue)>0) {
            switch ($searchKey) {
                case 'recipt_date':
                    $search .= "(a.last_receipt_date = '" . $searchValue . "') AND ";
                    break;
                case 'name':
                    $search .= "(name LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'roll_no':
                    $search .= "(roll_no = '" . $searchValue . "') AND ";
                    break;
                case 'doj':
                    $search .= "(a.doj = '" . $searchValue . "') AND ";
                    break;
                case 'insentive_amt':
                    $search .= "(a.insentive_amt = '" . $searchValue . "') AND ";
                    break;
                case 'insentive_date':
                    $search .= "(a.insentive_date = '" . $searchValue . "') AND ";
                    break;
                case 'insentive_status':
                    $search .= "(a.insentive_status = '" . $searchValue . "') AND ";
                    break;
            }
        }
    }

}

$sql = "SELECT 
	DATE_FORMAT(a.last_receipt_date, ".DATE_TIME_FORMAT.") AS recipt_date,
	a.roll_no,
	a.name,
	DATE_FORMAT(a.doj, ".DATE_FORMAT.") AS doj,
	IF(a.insentive_amt='',ROUND((a.total_fee*l.insentive/100), 2),a.insentive_amt) AS insentive_amt,
	IF(a.insentive_date = '0000-00-00','None',DATE_FORMAT(a.insentive_date, ".DATE_FORMAT.")) AS insentive_date,
	IF(a.insentive_status='','Pending','Approved') AS insentive_status  
FROM 
	admission a,
    login_accounts l 
WHERE 
    a.emp_id=l.id AND
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
while ($eachRow = mysql_fetch_assoc($res)) {
    $rows[] = $eachRow;
}
echo json_encode($rows);
