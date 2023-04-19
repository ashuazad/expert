<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
require_once '../includes/functions.php';
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
$flterInput = $_GET;
$where = 'lq.lead_id = l.id AND lq.user_id = la.id';
//Built SQL Filter 
$filterSql = '';
//Date Filter
if ((isset($flterInput['from_date']) && !empty($flterInput['from_date'])) && (isset($flterInput['to_date']) && !empty($flterInput['to_date']))) {
   $filterSql .= "(lq.created_on  >= '".$flterInput['from_date']." 00:00:00' AND lq.created_on <= '" . $flterInput['to_date'] ."  23:59:59') AND";   
}
//Branch Filter
if (isset($flterInput['branch']) && !empty($flterInput['branch'])) {
   $filterSql .= " (la.branch_id  = '".$flterInput['branch']."') AND";   
}
//Employee Filter
if (isset($flterInput['emp']) && !empty($flterInput['emp'])) {
   $filterSql .= " (lq.user_id  = '".$flterInput['emp']."') AND";   
}
//Phone Filter
if (isset($flterInput['phone']) && !empty($flterInput['phone'])) {
   $filterSql .= " (l.phone  = '".$flterInput['phone']."') AND";   
}

if (strlen($filterSql)>0) {
   $filterSql = rtrim($filterSql, "AND");
   $where .=  ' AND ' . $filterSql;
}

$columnList = array(
   'lq.id as id',
   'lq.total_price as total_price',
   'lq.offer_price as offer_price',
   '(total_price-offer_price) AS discount',
   'DATE_FORMAT(created_on, '.DATE_TIME_FORMAT.') as created_date',
   'l.name as lead_name',
   'l.phone as lead_phone', 
   'la.first_name as user_name', 
   'lq.status as status'
);
$smsList = $dbObj -> getData($columnList, '`lead_quotation` lq, `leads` l, `login_accounts` la ' , $where . " ORDER BY created_on DESC");
array_shift($smsList);
echo json_encode($smsList);