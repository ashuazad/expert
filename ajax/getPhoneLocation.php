<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/categoryDatabase.php';
require_once '../includes/userqueryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
session_start();
if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}

$id = $_SESSION['id'];
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$dbObj = new db();
$result = array('city'=>'NONE', 'country'=>'NONE');
$data = json_decode(file_get_contents("php://input"),true);

$posted_phone = $data['phone'];
$posted_ip = $data['id'];
$phone_location = getMobileStateV2($posted_phone);
if ($phone_location != 'None') {
    $result['city'] = $phone_location;
    $result['country'] = 'India';
    $dbObj->dataupdate(array('phone_location'=>$phone_location),'leads', "ip='".$posted_ip."'");
}
echo json_encode($result);