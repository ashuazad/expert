<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
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
$userAccDb = new useraccountDatabase();
$dbObj = new db();

$id = $_POST['id'];
$expensesArray = $dbObj -> getData( array("*","DATE_FORMAT(recipt_date, '%d-%m-%y') dt") , "bill_detail" , " id=" . $id );
if($expensesArray[0]>0){
    array_shift($expensesArray);
    echo json_encode($expensesArray[0]);
}