<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set("Asia/Kolkata");
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/categoryDatabase.php';
require_once '../includes/userqueryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
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

//file_get_contents('states.csv');
$CSVfp = fopen("states.csv", "r");
if($CSVfp !== FALSE) {
    while(! feof($CSVfp)) {
        $data = fgetcsv($CSVfp, 1000, ",");
        //print_r($data);
        $dbObj->dataInsert(array('state_code'=>$data['1'],'state'=>$data['0'],'country'=>'India'),'tele_state_code');
    }
}
fclose($CSVfp);