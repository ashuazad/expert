<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/sendgridMail.php';

session_start();
$post = file_get_contents('php://input');
$_POST = json_decode($post, true);
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$sendgridObj = new Sendgrid_Mail();

if(!empty($_POST['email_id']) && isset($_POST['email_id'])){
    $to_email[] = trim($_POST['email_id']);
    
    //Email Style List
    $emailStyle = $dbObj -> getData( array("*") , "email_template_style" , "new_lead_default = 1 AND type='newLead'"); 
    if($emailStyle[0]){
    array_shift($emailStyle);
    
    //Email Final content
    $sentHTML = str_replace( array('#name','#phone','#email'), array( $_POST['name'], trim($_POST['phone']), $_POST['email_id'] ), $emailStyle[0]['content']);
    $subject  = 'EXPERT™';
    $from     = 'admin@expertinstitute.in';
    $mailData = array(
                    'to'      => $to_email[0],
                    'to_list' => $to_email,
                    'from'    => $from,       
                    'subject' => $subject,         
                    'message' => $sentHTML,
                    'fromName'  => 'EXPERT™'
                    );

    echo  $sendgridObj->sendMail($mailData);
    }
}


//SMS
function smsApi( $phoneNo , $message = '' ){
 
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

function sendSMS($message, $phoneNo)
{
    $sql = "SELECT api FROM `sms_api` WHERE status=1";
    $result = mysql_query($sql);
    while($row = mysql_fetch_assoc($result)) {
        echo $row['api'];
        $apiUrl = str_replace(array('#phone','#message'), array($phoneNo,$message), $row['api']);
        callSMSAPI($apiUrl);
    }
}



if(!empty($_POST['phone']) && isset($_POST['phone'])){
    //SMS Details
    $smsDetails = $dbObj -> getData( array("*") , "sms" , "default_sms_new_lead = 1 AND type='newLead'"); 
    if($smsDetails[0]){
        array_shift($smsDetails);
        for($i=0;$i<count($smsDetails);$i++){
            //smsApi(trim($_POST['phone']),trim('Dear+'.urldecode($_POST['name']).','.$smsDetails[$i]['sms_content']));
            sendSMS(trim('Dear+'.urldecode($_POST['name']).',%20'.$smsDetails[$i]['sms_content']), trim($_POST['phone']));
        }
    }
}




?>
