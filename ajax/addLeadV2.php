<?php
error_reporting(E_ALL ^ E_DEPRECATED);
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
//print_r($_SESSION);
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$dbObj = new db();

$today = date("Y-m-d H:i:s");

$postData = json_decode(file_get_contents("php://input"),true);
$dataAry =$_GET;
$postData['create_date']=date("Y-m-d H:i:s");
$postData['emp_id']=$id;
$postData['status']='Start';
$ip_address = $_SERVER['REMOTE_ADDR'];
$postData['ip']=$ip_address;
$postData['assingment_data'] = date("Y-m-d");
$postData['source']='CRM';
$postData['branch_id']=$_SESSION['user_details']['branch_id'];

if(strlen($postData['phone']) == 10){
    $postData['phone_location'] = file_get_contents("https://www.advanceinstitute.co.in/getLocation.php?phone=".$postData['phone']);
}

$chekLed = $dbObj->getData( array("*") , "leads" , "phone='".$postData['phone']."'");
if( $chekLed[0]>0 ){
    $hitsNw = $chekLed[1]['hits']+1;
    $updateArray = array('hits' => $hitsNw ,'create_date'=>$postData['create_date'],'assingment_data '=>date("Y-m-d H:i:s"),'next_followup_date'=>date("Y-m-d H:i:s"));
    if($chekLed[1]['emp_id'] != 1){
        $updateArray['assingment_data'] = date("Y-m-d");
    }
    $dbObj->dataupdate( $updateArray  , 'leads' , "phone", $postData['phone'] );
}else{
    $addedLeadid = $dbObj->dataInsert($postData,"leads");
    $fwrLeadData = array();
    $fwrLeadData['object_id']   = $addedLeadid;
    $fwrLeadData['object_type'] ='LEAD';
    $fwrLeadData['currentId']   = $id;
    $fwrLeadData['nextId']      = $id;
    $fwrLeadData['frwDate']     = date("Y-m-d H:i:s");
    $fwrLeadData['modified_by'] = $id;
    $fwrLeadId = $dbObj->dataInsert($fwrLeadData,"leadfrwdhistory");
    $dbObj->dataupdate( array("frwId"=>$fwrLeadId), 'leads' , "id", $addedLeadid);
}
$result = array();
if(count($dataAry)>0){
    $curl = curl_init();
    $dataAry['email_id'] = $postData['email'];
    $dataJson = json_encode($dataAry);
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.advanceinstitute.co.in/ajax/sendMailSMSNewLead.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $dataJson,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 7939802c-89b2-47b6-b407-708615c8a8a4"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $result['errors'][] = $err;
    } else {
        $result['communication']['status'] = true;
        $result['communication']['response'] = $response;
    }
}

$curl = curl_init();

$message = '#' . $dataAry['Course'] . ' Lets Talk: 9718888700 to reserve seat.  for more details visit us at www.expertinstitute.in 

# OFFICIAL INVITATION. Attend 2hrs free training. on Laptop, Mobile, CCTV, LED/LCD TV Repairing Course tomorrow @ 11am. onwards. 

 ';
$message = urlencode($message);
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://panel.apiwha.com/send_message.php?apikey=BGLKP60U5L0RHVHR3RNI&number=91" . $dataAry['phone'] . "&text=" . $message,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    $result['errors'][] = $err;
}
$result['success'] = true;
echo json_encode($result);