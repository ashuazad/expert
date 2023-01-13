<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
require_once '../v11/lib/utilities.php';
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
$posted = json_decode(file_get_contents('php://input'),1);
//echo json_encode($data);
$today = date('Y-m-d');

$leadUpdate = array(
                    "name" => $posted['name'],
                    "address" =>  $posted['address'],
                    "category" =>  $posted['course'],
                    "email" =>  $posted['emailId'],
                    );
$return = array('success'=>false);
$errors = array();
if (strlen($posted['id'])>0) {
    $errors[] = "Invalid Lead Id";
}
if(count($errors)){
    if ($dbObj->dataupdate($leadUpdate, 'leads', "id", $posted['id'])) {
        $return['success'] = true;
        $leadUpdate['id'] = $posted['id'];
        $return['data'] = $leadUpdate;
    }
}
echo json_encode($return);