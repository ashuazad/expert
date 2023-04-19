<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
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
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$postedData = json_decode(file_get_contents('php://input'), true);
$lead_id = $postedData['lead_id'];
$columns = array('lq.id AS quotation_id','courses','total_price','offer_price','(total_price-offer_price) AS discount',"DATE_FORMAT(created_on, ".DATE_TIME_FORMAT.") as created_date", 'la.first_name', 'lq.status as status');
$quatation = $dbObj->getData($columns, 'lead_quotation lq, login_accounts la', ' lq.user_id=la.id AND lead_id = '.$lead_id);
array_shift($quatation);
echo json_encode($quatation);