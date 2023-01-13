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
$empArray = array('1' => 'Admin');
$accData = $dbObj->getData(array('id',"concat(first_name, ' ', last_name) AS name"),'login_accounts', ' branch_id is NOT NULL');
array_shift($accData);
foreach ($accData as $each) {
    $empArray[$each['id']] = $each['name'];
}
$dataColumns = array(
    'lds.id',
    'lds.phone',
    "DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt",
    "TRIM(TRAILING ' India' FROM phone_location ) AS phone_location",
    'phone_location',
    'hits',
    'emp_id',
    'status',
    'call_type',
    'user',
    'duration',
    'emp_id',
    "IF(ISNULL(message), 'None', message) AS message",
    'category',
    'r_status');
$nofr=$_POST['nfrPP'];
$pg=$_POST['pageN'];
$startLmt=($pg-1)*$nofr;
//print_r($_POST);
//echo $startLmt;
if(strlen($_POST['whereCnd'])){
    $where=$_POST['whereCnd'];
    $resultCountLead = mysql_fetch_assoc(mysql_query("SELECT COUNT(id) as leadCount FROM leads WHERE ".$where));
    $getDataLeads=$dbObj->getData($dataColumns,"leads lds, ivr_lead ivr","lds.phone = ivr.phone AND source = 'IVR' AND ( " .$where." )  order by create_date desc limit $startLmt,$nofr" );
}else{
    $getDataLeads=$dbObj->getData($dataColumns,"leads lds, ivr_lead ivr"," lds.phone = ivr.phone AND source = 'IVR' order by create_date desc limit $startLmt,$nofr");
}
$returnArray = array();
array_shift($getDataLeads );
foreach ($getDataLeads as $eachLead) {
    $eachLead['emp_name'] = isset($empArray[$eachLead[emp_id]]) ? $empArray[$eachLead[emp_id]] : 'Admin';
    $eachLead['is_assign'] = (!isset($empArray[$eachLead[emp_id]]) || ($eachLead[emp_id]==1))? 0 : 1;
    $returnArray[] = $eachLead;
}
echo json_encode($returnArray);
?>
