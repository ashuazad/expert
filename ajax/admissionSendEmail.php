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
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$Sendgrid_Mail = new Sendgrid_Mail();

if(strlen($_POST['whereCnd'])){
$where=$_POST['whereCnd'];
$sms=$_POST['email'];
$emailDetail = $dbObj->getData( array('*') , 'email_template' , " email_title = '".trim($_POST['email'])."' AND email_category = 'admission'" );
$emailStyle = $dbObj->getData( array("*") , "email_template_style" , "title = '".trim($_POST['email_style'])."'");
    if($emailDetail[0] > 0 ){
        array_shift($emailDetail);
        array_shift($emailStyle);
        
         $admissionData = $dbObj->getData(array('*'),"admission",$where , true);  
        $nofRows = $admissionData[0];
        $to_email = array();
            if($nofRows > 0 ){
                array_shift($admissionData);
                    foreach($admissionData as $eachLead){
                            $to_email[] = $eachLead['email_id'];
                            $phone = trim($eachLead['phone']);
                            $sName = trim($eachLead['name']);
                        }
            }

    }
}

if(count($to_email) > 0){
//Email Basic Content
$content = $emailDetail[0]['email_content'];

//Prepairing content
$text = array('#name','#content', '#phone');
$replace = array($sName ,$content, $phone);

//Email Settings
$emailSetting = $Sendgrid_Mail->getAdmEmailSettings();
//Email Final content
$sentHTML = str_replace( $text , $replace , $emailStyle[0]['content']);
    $subject  = $emailSetting['admissionSubject'];
    $from     = $emailSetting['admissionEmail'];
$mailData = array(
                'to'      => $eachLead['email_id'],
                'to_list' => $to_email,
                'from'    => $from,       
                'subject' => $subject,         
                'message' => $sentHTML,
                'fromName'=> $emailSetting['fromName']
                );

print_r($mailData);

$Sendgrid_Mail->sendMail($mailData);
}
function sendMail( $email_id , $message = '' , $subject = ''){
            
            /*$messageFinal='<html>
<head></head>
<body style="background-color:#d5d7d9">
	<div id="wrapper" style="margin:30px auto;width:70%;">
		<div id="headerLeft " style="float:left;width:54%;height:124px;background-color:#ffffff;font-family: Verdana;font-size:12px; padding: 10px;text-align: left;border-top-left-radius: 10px;">
			

				<strong style="font-size: 20px;font-weight:bold; margin-left:auto; color:#060;font-family: Verdana;">EXPERT</strong>
				<br>	
				<p>
				<strong style="font-weight:bold;  margin-left:auto; color:#060;font-family: Verdana;" >Head Office :</strong> 
				<br>	
                2453,Hudson Line, Kingsway Camp,<br> Near G.T.B Nagar Metro Station Gate No.4 <br>New Delhi-110009 ( India ) Ph : 011-47814776</p>			

			
		</div>
		<div id="headerRight" style="float:left;width:40%;height:124px;background-color:#ffffff;padding: 10px 0px 10px 0px;border-top-right-radius: 10px;">
			<img src="http://www.expertindia.in/superadmin/scriptrecpt/logo2.gif">
		</div>
		
		<div id="content" style="float:left;width:94%;height:124px;background-color:#ffffff;font-family: Verdana; padding: 10px;" >
			'.$message.'
		</div>
		<div id="footer" style="float:left;width:94%;height:auto;background-color:#ffffff;font-family: Verdana; padding: 10px;font-size:12px;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;" >
		

       	  <h2><span>Important Notes</span></h2>
       	 <hr>
         	<ol>
         		<li>Cheque/DD deposited as fee must be in favour of "Expert Institute of advance Technologies Pvt.Ltd."</li>
         		<li>In case cheque deposit as fee dishonoured in the bank a fine of Rs.500/- will be charged.</li>
         		<li>Fees will not be refundable in any circumstances.</li>
         		<li>In case of delay in submitting fees installment, a fine of Rs.30/- per day will be chareged.</li>
         		<li>If a student is absent from the class or takes leave without information no extra classes will be provided to him.</li>
         		<li>Admission in the class room will not be allowed without Student Progress Report.</li>
         		<li>Student will be provided with an ID and Password to login at www.expertinstitute.in to check all the details regarding online training, latest updates and all the videos and solutions related to their course.</li>
         		<li>All disputes are subject to Delhi jurisdiction only.</li>
         	</ol>



			</div>

		
		</div>
	</div>
	

</body>
</html>';*/
            
           $emailFrm = "EXPERT <admin@expertinstitute.in>";
           $headers  = "MIME-Version: 1.0\r\n";
	       $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";       
	       $headers .= "From: ".$emailFrm." \r\n";
  	       $to = $email_id;
          echo mail($to , $subject, $message, $headers);
           
}
?>
