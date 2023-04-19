<?php
class sendSMS{
	protected $success = null;	
	 function __construct($smsNo = null , $smsContent = null , $params = array()){
	 	if(empty($smsNo) || empty($smsContent)){
	 		$this->success = false;
	 	}else{ 	/*		 		
	 			$_POST['user'] = $user = 'gT1vZzN68U664oB57G4Wgg';
	 			$_POST['senderid'] = $senderid = 'EXPERT';
	 			$_POST['channel'] = $channel = 2;
	 			$_POST['DCS'] = $DCS = 1;
	 			$_POST['flashsms'] = $flashsms = 0;
	 			$_POST['number'] = $number = $smsNo;
	 			$_POST['message'] = $message = $smsContent;
	 			$_POST['route'] = $route = 1;
unset($_POST['smsNo']);	
unset($_POST['smsContent']);
print_r($_POST);
	 			$ch=curl_init('http://login.smsgatewayhub.com/api/mt/SendSMS');

	 			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 			curl_setopt($ch,CURLOPT_POST,1);
	 			curl_setopt($ch,CURLOPT_POSTFIELDS,$_POST);
	 			curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
	 			$data = curl_exec($ch);
	 		*/
	 	
	 	     $this->smsApi($smsNo,urlencode($smsContent));   
	 	        
	 		file_get_contents('http://smsapi.expertinstitute.in/api/mt/SendSMS?APIKey=gT1vZzN68U664oB57G4Wgg&senderid=EXPERT&channel=2&DCS=1&flashsms=0&number='.$smsNo.'&text='.urlencode($smsContent).'&route=1');

	 	}
	}
	
	function smsApi( $phoneNo , $message = '' ){
 
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://smsapi.expertinstitute.in/api/mt/SendSMS?APIKey=gT1vZzN68U664oB57G4Wgg&senderid=EXPERT&channel=2&DCS=0&flashsms=0&number=91".trim($phoneNo)."&text=".trim($message)."&route=1",
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
}
