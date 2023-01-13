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
$sendgridObj = new Sendgrid_Mail();
//print_r($_POST);
//echo $startLmt;
if(strlen($_POST['whereCnd'])){
   // echo 'jii';
$where=$_POST['whereCnd'];
$sms=$_POST['email'];
$emailDetail = $dbObj->getData( array('*') , 'email_template' , " email_title = '".trim($_POST['email'])."'" );

//Email Style List
$emailStyle = $dbObj -> getData( array("*") , "email_template_style" , "title = '".trim($_POST['email_style'])."'"); 

//print_r($emailDetail);
    if($emailDetail[0] > 0 ){
        //echo 1;
        array_shift($emailDetail);
        array_shift($emailStyle);
        $resultCountLead = mysql_fetch_assoc(mysql_query("SELECT COUNT(id) as leadCount FROM leads WHERE ".$where));
        $getDataLeads=$dbObj->getData(array('id','name','email','phone','category','r_status',"DATE_FORMAT(create_date, '%d-%m-%y | %r') cdt" ,'emp_id','status' ),"leads",$where."  order by create_date desc " );
        $nofRows = $getDataLeads[0];
        //print_r($getDataLeads);
            if($nofRows > 0 ){
                //echo 2;
                array_shift($getDataLeads);
                    $to_email = array();
                    foreach($getDataLeads as $eachLead){
                            
                            $emailHead = trim($eachLead['name']); 
                            $phone = trim($eachLead['phone']);
                            $to_email[] = $eachLead['email'];        
                            
                            /*
                            $content = $emailDetail[0]['email_content'];
                            $text = array('#name','#content');
                            $replace = array($emailHead ,$content);                            
                            
                            $sentHTML = str_replace( $text , $replace , $emailStyle[0]['content']);
                            print_r($sentHTML);
                            sendMail( $eachLead['email'] , $sentHTML , 'EXPERT™' );*/
                            
                        }
            }
    }
}
//Email Basic Content
$content = $emailDetail[0]['email_content'];

//Prepairing content
$replace = array($emailHead ,$content ,$phone);
$text = array('#name','#content', '#phone');
//Email Settings
$emailSetting = $sendgridObj->getLeadEmailSettings();
//Email Final content
$sentHTML = str_replace( $text , $replace , $emailStyle[0]['content']);
/*$subject  = 'EXPERT™';
$from     = 'admin@expertinstitute.in';*/
$subject  = $emailSetting['leadSubject'];
$from     = $emailSetting['leadEmail'];
$mailData = array(
                'to'      => $eachLead['email'],
                'to_list' => $to_email,
                'from'    => $from,       
                'subject' => $subject,         
                'message' => $sentHTML,
                'fromName'=> $emailSetting['fromName']
                );

print_r($mailData);

$sendgridObj->sendMail($mailData);

function sendMail( $email_id , $message = '' , $subject = ''){
            
/*            $messageFinal='<html>
<head></head>

<table width="86%" align="center" border="0" cellspacing="0" cellpadding="0">
			<tbody><tr><td align="left"><img width="220px" height="65px" src="https://ci5.googleusercontent.com/proxy/XcH5fbr6xMbTSmP5BdgKKybOaJ3RHjdbExuH-k4sczeTvaN-EU9GdCM9TfIXwYhbMPDpi5HZp8GLZyRT4v2vcqDGI0bjPvzH1LjscOHesw7H16OT9THtseK3=s0-d-e1-ft#https://www.expertinstituteindia.in/superadmin/scriptrecpt/logo2.gif" style="display:block" class="m_-910387193246731275m_8167436602791968611m_-283611404277071886CToWUd m_-910387193246731275m_8167436602791968611CToWUd m_-910387193246731275CToWUd CToWUd"></td><td align="right"><img width="32px" height="32px" style="display:block" src="https://ci6.googleusercontent.com/proxy/DxxsF9gJ_wuvBUnr_tF8GZsT2aAOQRHF-ySRix9VJTnF_qYqd_dNwIoiRM9OcOrph4-s1MXYkOqyNgb3obOjV_i5=s0-d-e1-ft#http://www.expertinstitute.co.in/img/xyz.png" class="m_-910387193246731275m_8167436602791968611m_-283611404277071886CToWUd m_-910387193246731275m_8167436602791968611CToWUd m_-910387193246731275CToWUd CToWUd"></td></tr>

			<tr>
			</tr><tr><td style="padding-bottom:18px;padding-left:12px;background:#fafafa">
                    
                  </td><td style="background:#fafafa"></td></tr>
                  <tr>
                    
                  </tr>
                  <tr>
                    
                  </tr>
                
              </tbody></table>
<table style="font-weight:normal;font-family:Arial,Helvetica,sans-serif;color:#444444!important;line-height:19px;font-size:13px;background-color:white!important;border-bottom-color:#f7f7f7;border-bottom-style:solid;border-left-color:#f7f7f7;border-bottom-width:10px;border-left-style:solid;border-top-width:10px;border-left-width:10px;border-top-color:#f7f7f7;border-right-width:10px;border-right-color:#f7f7f7;border-right-style:solid;border-top-style:solid" id="m_5514119351707868283email-container" width="100%" bgcolor="white !important" cellspacing="0" border="0" cellpadding="0" align="center">
                <tbody><tr>
                    <td style="border-collapse:collapse">
                        <table style="font-weight:normal;font-family:Arial,Helvetica,sans-serif;color:#444444!important;line-height:19px;font-size:13px" id="m_5514119351707868283content" width="100%" bgcolor="#ffffff" cellspacing="0" border="0" cellpadding="0">
                            <tbody><tr>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="2%" class="m_5514119351707868283empty">&nbsp;</td>
                                <td style="border-collapse:collapse" height="20" width="96%">
                                    <table style="font-weight:normal;font-family:Arial,Helvetica,sans-serif;color:#444444!important;line-height:19px;font-size:13px" width="100%" bgcolor="#ffffff" cellspacing="0" border="0" cellpadding="0">
                                        <tbody><tr><td style="line-height:0;font-size:0;border-collapse:collapse" height="27" class="m_5514119351707868283empty">&nbsp;</td></tr>

                                        <tr>
                                            <td colspan="2" style="background:#4a90e2;padding:30px 30px 30px 30px">
                      <h1 style="font-weight:300;color:white;line-height:35px">
                        Laptop Tablet PC Mobile Projector CCTV Training<br>
                                              </h1>
                    </td>
                                        </tr>
                                        <tr><td style="line-height:0;font-size:0;border-collapse:collapse" height="30" class="m_5514119351707868283empty">&nbsp;</td></tr>

										<img height="86" width="101" src="https://ci3.googleusercontent.com/proxy/hI5MMgrdwMSoS4Fl8XWK-hiyCfDCc34qv4LzKBCAPdjBNuBFvcOlskyjS6d5yavIHsJn5Ozz5z2cxv8UzzHCHxfcyaSETnwFozvGUYRU0RwRKQzvln53fgji_DAJ9Yc4NPjOqHi4JosBhgBb3CBuETK2om8v7WcV=s0-d-e1-ft#https://www.expertinstituteindia.in/superadmin/img/4fcd24_b18a74c00e6b438ba5a2701de9536874_mv2.png" style="float:right" class="m_-6149606813133854796m_8167436602791968611CToWUd m_-6149606813133854796CToWUd CToWUd">
                                        <tr>
                                            <td align="center" class="m_-8104447296473799234paraheading" style="color:#757575;font-family:&quot;Roboto&quot;,OpenSans,&quot;Open Sans&quot;,Arial,sans-serif;font-size:18px;font-weight:300;line-height:33px;margin:0;padding:0 25px 0 25px;text-align:center">'.$message.'</td>
                                        </tr>

                                    <tr>
                                    
                                            <td style="border-collapse:collapse">
                                                <table style="font-weight:normal;font-family:Arial,Helvetica,sans-serif;color:#444444!important;line-height:19px;font-size:13px" class="m_5514119351707868283btn" cellspacing="0" border="0" cellpadding="0" align="center">
                                                    <tbody><tr style="font-size:0;line-height:0;border-collapse:collapse" height="5" class="m_5514119351707868283empty"><td style="border-collapse:collapse" colspan="3">&nbsp;</td></tr>
                                                    <tr height="36">
                                                        <td style="border-collapse:collapse" height="36" width="3" bgcolor="#4285f4" class="m_5514119351707868283btn-left"><img style="display:block;outline:none;text-decoration:none" height="36" width="3" src="https://ci4.googleusercontent.com/proxy/kYDb9YsOE3c7Rt9FJQsM4ocQdUNsg1_BedGMVzacwEVmjDxBKCYJbGngUDpQ2slEDn43LC7OGhVmcaSzEGEikuzRY7evzjbU_bhTJGU=s0-d-e1-ft#http://www.gstatic.com/gmktg/mtv-img/left_of_button.png" class="m_5514119351707868283image_fix CToWUd" border="0"></td>
                                                        <td style="border-collapse:collapse" bgcolor="#4285f4">
                                                            <table style="font-weight:normal;font-family:Arial,Helvetica,sans-serif;color:#444444!important;line-height:19px;font-size:13px" class="m_5514119351707868283btn-inner" cellspacing="0" border="0" cellpadding="0" align="center">
                                                    		<tbody><tr>
                                                                
                                                        		
                                                    				<table style="margin-right:0;font-family:Arial,Helvetica,sans-serif;font-weight:normal;margin-top:0;padding-top:0;padding-left:0;padding-bottom:0;padding-right:0;color:#444444!important;font-size:13px;line-height:19px;border-collapse:collapse;margin-left:0" cellspacing="0" border="0" cellpadding="0" align="center">
                                                    					<tbody>
                                                    					<tr>
                                                    						<td style="line-height:20px;border-collapse:collapse" height="20" align="center" valign="middle">
                                                    							<a href="http://www.expertinstitute.in" style="margin-bottom:0;padding-left:0;display:block;margin-right:0;padding-top:0;padding-bottom:0;margin-left:0;padding-right:0;margin-top:0;font-family:Arial,Helvetica,sans-serif;font-weight:normal;text-align:center;color:#ffffff;font-size:13px;text-decoration:none" class="m_5514119351707868283thebtn" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.google.com/appserve/mkt/p/AFvIGUfX0E59bg9TBQ8tRkZC32tMXfeUSO1Nf97OK1QQb7j_LooW4Nnq6fKKbboHg8F99mahEoGaw7To9MGEe0utGAz2vjOeJ-psRhPERb3-_E81D2_AAGbdA_mW4NdArrDvjyDJhNrt0MqMZtEbY3uMjQfdW1_WBzgVVvohkTTQ1jmyZpeb44GvU7IEv4kJu-AGB_RKLr3gYy8nNLNa0bCudMDBsUjcTpZI8drppUqNRQQDJqkhMmXvNvk4C88XtolxhyepiVMneU8ucKSReDLkmLSIJV786bgnxwjhVygvIakpUaJmUxV1Gy9seuzGa30Qu-VkIc8u_JJJXZHSrBGJ4Hm_VOgdybkja0t8uOUlfp0OR9un5fGRTrJsWEReCvtW7sQJxHtxfYc&amp;source=gmail&amp;ust=1512846120090000&amp;usg=AFQjCNGb5pAWqDbk5Rd6eQhy8KDp3xkjQw">Book Online Get 30% Off</a>
                                                    							</td>
                                                    					</tr>
                                                    					</tbody>
                                                    				</table>
                                                    			
                                                    			</td>
                                                    		</tr>
                                                    		</tbody></table>
                                                    	</td>
                                                    	<td style="border-collapse:collapse" height="36" width="3" bgcolor="#4285f4" class="m_5514119351707868283btn-right"><img style="display:block;outline:none;text-decoration:none" height="36" width="3" src="https://ci3.googleusercontent.com/proxy/JGksWoJCWIPP08REoyot7H4__6XVagEilMllNgMNCy7_FXNhAb0crTQEkDiMotnSK-8lC9qFEp_jHHxyzXA5U4jIqoaGyTSPBYLtWquJ=s0-d-e1-ft#http://www.gstatic.com/gmktg/mtv-img/right_of_button.png" class="m_5514119351707868283image_fix CToWUd" border="0"></td>
                                                    </tr>
                                                    <tr style="font-size:0;line-height:0;border-collapse:collapse" height="5" class="m_5514119351707868283empty"><td style="border-collapse:collapse" colspan="3">&nbsp;</td></tr>
                                                </tbody></table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border-collapse:collapse">
                                                <p style="font-weight:normal;font-family:Arial,Helvetica,sans-serif;color:#444444!important;line-height:19px;font-size:13px;margin-top:1em;margin-bottom:1em;margin-right:0;margin-left:0">With Regards,<br>

            

                                                   Expert Team                                                </p>

                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="2%" class="m_5514119351707868283empty">&nbsp;</td>
                            </tr>
                        </tbody></table>
                        <table style="font-size:4px" id="m_5514119351707868283footermark" height="4" width="100%" bgcolor="#ffffff" cellspacing="0" border="0" cellpadding="0">
                            <tbody><tr>
                                <td style="height:4px;font-size:0;line-height:4px;border-collapse:collapse" width="72%" height="4" class="m_5514119351707868283empty">&nbsp;</td>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="6%" height="4" bgcolor="#4285f4" class="m_5514119351707868283fm-blue m_5514119351707868283empty">&nbsp;</td>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="6%" height="4" bgcolor="#EA4335" class="m_5514119351707868283fm-red m_5514119351707868283empty">&nbsp;</td>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="6%" height="4" bgcolor="#FBBC05" class="m_5514119351707868283fm-yellow m_5514119351707868283empty">&nbsp;</td>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="6%" height="4" bgcolor="#34A853" class="m_5514119351707868283fm-green m_5514119351707868283empty">&nbsp;</td>
                                <td style="height:4px;font-size:0;line-height:4px;border-collapse:collapse" width="7%" height="4" class="m_5514119351707868283empty">&nbsp;</td>
                            </tr>
                        </tbody></table>

                        <table style="margin-top:0!important;font-size:10px;color:#666666!important" id="m_5514119351707868283footer" width="100%" bgcolor="#f7f7f7" cellspacing="0" border="0" cellpadding="0">
                            <tbody><tr>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" height="20" class="m_5514119351707868283empty" colspan="3">&nbsp;</td>
                            </tr>

                            <tr>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="2%" class="m_5514119351707868283empty">&nbsp;</td>
                                <td style="border-collapse:collapse" id="m_5514119351707868283footer-text" height="20" width="96%">
                                     <p style="margin-bottom:1em;margin-right:0;font-weight:normal;font-family:Arial,Helvetica,sans-serif;margin-top:0;color:#666666!important;line-height:19px;font-size:12px;margin-left:0">
			                     <a href="https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg" style="text-decoration:none;color:#4285f4" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg&amp;source=gmail&amp;ust=1512846120090000&amp;usg=AFQjCNGn5FLmVjck9Giy6M8LkVD5H1PIjw">Call Google</a> | <a href="https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg" style="text-decoration:none;color:#4285f4" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg&amp;source=gmail&amp;ust=1512846120090000&amp;usg=AFQjCNGn5FLmVjck9Giy6M8LkVD5H1PIjw">Chat</a> | <a href="https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg" style="text-decoration:none;color:#4285f4" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg&amp;source=gmail&amp;ust=1512846120090000&amp;usg=AFQjCNGn5FLmVjck9Giy6M8LkVD5H1PIjw">Email</a> | <a href="https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg" style="text-decoration:none;color:#4285f4" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg&amp;source=gmail&amp;ust=1512846120090000&amp;usg=AFQjCNGn5FLmVjck9Giy6M8LkVD5H1PIjw">Ask Experts</a> | <a href="https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg" style="text-decoration:none;color:#4285f4" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.google.com/appserve/mkt/p/AFvIGUdg5YhUKm7GwfuI7RfCb_8kSr-LxJZGKPTCu7_uKem_syuNfPjOln63u1trPKu7Zio1CPkGxE82M9e3oHMiTNkqybqSgxbllgwH1BEPSIWY0VV5lSPFVyANVg&amp;source=gmail&amp;ust=1512846120090000&amp;usg=AFQjCNGn5FLmVjck9Giy6M8LkVD5H1PIjw">Learn</a>
                                    </p>
                                    <p style="margin-bottom:1em;margin-right:0;font-weight:normal;font-family:Arial,Helvetica,sans-serif;margin-top:0;color:#666666!important;line-height:19px;font-size:10px;margin-left:0">© 2017 EXPERT . <a href="http://www.expertinstitute.in/contact.html">2453, Hudson Line, Top Floor, Kingsway Camp,
(Near G.T.B Nagar Metro Station Gate no 4) 
New Delhi - 110009 
                                    </p>
                                    
                                </td>
                                <td style="line-height:0;font-size:0;border-collapse:collapse" width="2%" class="m_5514119351707868283empty">&nbsp;</td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
              
		
		
	
	

</div>
	
</html>';*/
            
           $emailFrm = "EXPERT™ <admin@expertinstitute.in>";
           $headers  = "MIME-Version: 1.0\r\n";
	       $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";       
	       $headers .= "From: ".$emailFrm." \r\n";
  	       $to = $email_id;
          echo mail($to , $subject, $message, $headers);
           
}
?>
