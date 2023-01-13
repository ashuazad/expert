<?php
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
function getIpLoc( $ip ){
    $return = array("city"=>"NONE","country"=>"NONE");
    // create curl resource
    $ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, "https://traceip.bharatiyamobile.com/trace-ip-address.php?ip=".$ip);
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);
    //Get Country
    $findingTextA = "Country:</td><td>";
    $startPos = strpos($output , $findingTextA);
    if (!$startPos) {
        curl_close($ch);
        return $return;
    }
    $endPos = strpos($output , "</td>" , $startPos+strlen($findingTextA) );
    $stateLength = $endPos - ($startPos+strlen($findingTextA));
    $address = substr($output , $startPos+strlen($findingTextA) , $stateLength);
    //Get City
    $findingTextB = "City:</td><td>";
    $startPos = strpos($output , $findingTextB);
    if (!$startPos) {
        curl_close($ch);
        return $return;
    }
    curl_close($ch);
    $endPos = strpos($output , "</td>" , $startPos+strlen($findingTextB) );
    $stateLength = $endPos - ($startPos+strlen($findingTextB));
    $cityName=substr($output , $startPos+strlen($findingTextB) , $stateLength);
    $return["city"] =(strlen($cityName))?$cityName:'NONE';
    if(strlen($cityName)){
        $return["country"] = $address;
    }
    return $return;
}
$id = $_SESSION['id'];
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$dbObj = new db();

$data = json_decode(file_get_contents("php://input"),true);

$ip_posted = $data['ip'];
$ipDetail = $dbObj->getData(array('address'),'ip_location', "ip='".$ip_posted."'");
if ($ipDetail[0]) {
    echo $ipDetail[1]['address'];
} else {
    $ipLocation = getIpLoc($ip_posted);
    if ($ipLocation['city'] != 'NONE' || $ipLocation['country'] != 'NONE') {
        $dbObj->dataInsert(array('ip'=>$ip_posted, 'address'=>json_encode($ipLocation)),'ip_location');
    }
    $ipLocation['i']='1';
    echo json_encode($ipLocation);
}