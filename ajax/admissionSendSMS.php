<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
echo "hello";
//echo $startLmt;
print_r($_POST);
if(strlen($_POST['whereCnd'])){
   // echo 'jii';
$where=$_POST['whereCnd'];
$sms=$_POST['sms'];
$smsDetail = $dbObj->getData( array('*') , 'sms' , " sms_title = '".trim($_POST['sms'])."'" );
//print_r($smsDetail);
    if($smsDetail[0] > 0 ){
        //echo 1;
        array_shift($smsDetail);
        //$resultCountLead = mysql_fetch_assoc(mysql_query("SELECT COUNT(id) as leadCount FROM leads WHERE ".$where));
        //$getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt" ,'emp_id','status' ),"leads",$where."  order by create_date desc " );
        $admissionData = $dbObj->getData(array('*'),"admission",$where);  
        $nofRows = $admissionData[0];
        //print_r($getDataLeads);
            if($nofRows > 0 ){
                //echo 2;
                array_shift($admissionData);
                    foreach($admissionData as $eachLead){
                            $smsHead = '';
                            $smsHead = urlencode('Dear '.trim($eachLead['name']).', '); 
                             $smsContent = $smsHead.$smsDetail[0]['sms_content'];
                            //echo "\n";
                            //echo $eachLead['phone']," ",$smsContent;
                /*            echo smsAPI($eachLead['phone'],$smsContent);
                            echo "\n";
                            echo smsAPI2($eachLead['phone'],$smsContent);
                            echo "\n";*/
                            sendSMS($smsContent,$eachLead['phone']);
                        }
            }
    }
}


function callSMSAPI($apiURL)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL,
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
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
}

/**
 * @param $message
 * @param $phoneNo
 */
function sendSMS($message, $phoneNo)
{
    $sql = "SELECT api FROM `sms_api` WHERE status=1";
    $result = mysql_query($sql);
    while($row = mysql_fetch_assoc($result)) {
        //echo $row['api'];
        $apiUrl = str_replace(array('#phone','#message'), array($phoneNo,$message), $row['api']);
        callSMSAPI($apiUrl);
    }
}


function smsAPI( $phoneNo , $message = '' ){
 
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://sms.expertinstitute.in/submitsms.jsp?user=expertin&key=e5869861f6XX&mobile=".$phoneNo."&message=".$message."&senderid=EXPERT&accusage=1",
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
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
    
}

function smsAPI2( $phoneNo , $message = '' ){

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://api.expertinstitute.in/api/sendhttp.php?authkey=145165ANeFlyZKTNW58ca7bbd&mobiles=91".trim($phoneNo)."&message=".trim($message)."&sender=EXPERT&route=4&country=0",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "postman-token: 3c2cdc62-c501-808b-3138-72f12ad930f1"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
} 


?>
