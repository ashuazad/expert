<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
date_default_timezone_set('Asia/Kolkata');
$id = $_SESSION['id'];
db::getDBconnect();
$phpInput = file_get_contents('php://input');
$requestData = json_decode($phpInput,true);
$delete_ips = $requestData['delete_ips'];
$str_delete_ips = "'" . implode("','",$delete_ips) . "'";
$sqlDelete = "DELETE FROM visitIps WHERE ip IN(" . $str_delete_ips . ")";
mysql_query($sqlDelete);
$responseData = array("success"=>true);
echo json_encode($responseData);