<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
require_once '../v11/lib/utilities.php';
require_once '../includes/userPermissions.php';
session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
//$id = 34;
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$loadDeadAdmissions = false;
$where = ' 1=1 ';
$posted = json_decode(file_get_contents('php://input'),1);
//echo json_encode($data);

$column = array(
            "ep.id AS ID",
            "CONCAT(ep.first_name, ' ', ep.last_name) AS EMP_NAME",
            "br.id AS BRANCH_ID",
            "br.branch_name AS BRANCH_NAME"
            );

#### Filter ###
$filter = '';
if ((isset($_GET['fromDate']) && !empty($_GET['fromDate'])) && (isset($_GET['toDate']) && !empty($_GET['toDate']))) {
    $filter .= "(next_due_date>='" . $_GET['fromDate'] . "' AND next_due_date<='".$_GET['toDate']."') AND ";
}

if (isset($_GET['empId']) && !empty($_GET['empId'])) {
    $filter .= "(emp_id ='" . $_GET['empId'] . "') AND ";
}

if (isset($_GET['phone']) && !empty($_GET['phone'])) {
    $filter .= "(phone ='" . $_GET['phone'] . "') AND ";
}

if (isset($_GET['EMP_NAME']) && !empty($_GET['EMP_NAME'])) {
    $filter .= "(CONCAT(ep.first_name, ' ', ep.last_name) LIKE '%" . $_GET['EMP_NAME'] . "%') AND ";
}

if (strlen($filter)>0) {
    $filter = rtrim($filter, 'AND ');
    $where .= ' AND ( '.$filter.' ) ';
}
#### Filter ###

##### Search ######
$search = '';
if (isset($_GET['param']) && !empty($_GET['param'])) {
    foreach ($_GET as $searchKey => $searchValue) {
        if (strlen($searchValue)>0) {
            switch ($searchKey) {
                case 'roll_no':
                    $search .= "(roll_no = '" . $searchValue . "') AND ";
                    break;
                case 'name':
                    $search .= "(name LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'phone':
                    $search .= "(phone LIKE '%" . $searchValue . "%') AND ";
                    break;
                case 'admDate':
                    $search .= "(date(doj) = '" . $searchValue . "') AND ";
                    break;
                case 'dueDate':
                    $search .= "(next_due_date = '" . $searchValue . "') AND ";
                    break;
                case 'courses':
                    $search .= "(course LIKE '%" . str_replace(' ', '-', $searchValue) . "%') AND ";
                    break;
                case 'total_fee':
                    $search .= "(total_fee = '" . $searchValue . "') AND ";
                    break;
                case 'credit_amt':
                    $search .= "((total_fee-due_fee) = '" . $searchValue . "') AND ";
                    break;
                case 'due_fee':
                    $search .= "(due_fee = '" . $searchValue . "') AND ";
                    break;
                case 'message':
                    $search .= "(message LIKE '%" . $searchValue . "%') AND ";
                    break;
            }
        }
    }
}
if (strlen($search)>0) {
    $search = rtrim($search, 'AND ');
    $where .= ' AND ( '.$search.' ) ';
}
##### Search ######
##### Get User Permissions ######
function getUserPermissions(&$user, $key){
    $objUserPermissions = new userPermissions($user['ID']);
    $user['permissions'] = $objUserPermissions->userPermission;
}
##### Get User Permissions ######
$usersArray = array();
$usersArray=$dbObj->getData($column," `login_accounts` ep JOIN `login_accounts` br  ON (ep.branch_id=br.id) ", $where);
array_shift($usersArray);
array_walk($usersArray,'getUserPermissions');
/*foreach ($usersArray as $user) {
    $objUserPermissions = new userPermissions($user['ID']);
    $objUserPermissions->userPermission;
}*/
echo json_encode($usersArray);