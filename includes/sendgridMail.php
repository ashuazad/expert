<?php 
date_default_timezone_set('Asia/Kolkata');
class Sendgrid_Mail{
    
    function sendMail( $data = array() ){
        /*
        $url = 'https://api.sendgrid.com/';
        $user = 'admin@expertinstitute.in';
        $pass = 'password@2019@)!(';
        
        $json_string = array('to' => $data['to_list']);
       $fromName = (isset($data['fromName']) && !empty($data['fromName'])) ? $data['fromName'] : 'EXPERT™';
        $params = array(
            'api_user'  => $user,
            'api_key'   => $pass,
            'x-smtpapi' => json_encode($json_string),
            'to'        => $data['to'],
            'subject'   => $data['subject'],
            'html'      => $data['message'],
            //'from'      => 'admin@expertinstitute.in',
            'from'      => $data['from'],
            //'fromname'  => 'EXPERT™'
            'fromname'  => $fromName
          );
        
        
        $request =  $url.'api/mail.send.json';
        */
        $fromName = (isset($data['fromName']) && !empty($data['fromName'])) ? $data['fromName'] : 'EXPERT™';
        $toArray = array();
        if (is_array($data['to_list'])) {
        	foreach($data['to_list'] as $eachEmail){
        		$toArray[] = array("email"=>$eachEmail);
        	}
        } else {
        	$toArray[] = array("email"=>$data['to_list']);
        }
        
        $postData = array("personalizations"=>array(
        										array(
        											"to"=>$toArray
        											)
        									),
        			"from" => array("email"=>$data['from'],"name"=>$fromName),
        			"subject" => $data['subject'],
        			"content" => array(
        								array(	
        									"type"=>'text/html',
        									"value"=>$data['message']
        									)
        							)						
        			);

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.sendgrid.com/v3/mail/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($postData,JSON_UNESCAPED_SLASHES),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer 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'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
    	error_log( "\n" . print_r( date('y-m-d H:i:s') . ' ' . json_encode($json_string['to']),true) . ':' . print_r($response,true), 3, 'sendGridMail.log' );	
    }

    function getLeadEmailSettings()
    {
        mysql_set_charset('utf8');
        $leadSettings = array('leadSubject'=>'EXPERT™','leadEmail'=>'admin@expertinstitute.in','fromName'=>'EXPERT™');
        $sqlEmail = "SELECT setting_group,setting_value,from_name FROM email_settings WHERE setting_group IN('leadEmail','leadSubject') AND status=1";
        $result = mysql_query($sqlEmail);
        while ($row = mysql_fetch_assoc($result)) {
            if ($row['setting_group'] == 'leadSubject') {
                $leadSettings['leadSubject'] = $row['setting_value'];
            }
            if ($row['setting_group'] == 'leadEmail') {
                $leadSettings['leadEmail'] = $row['setting_value'];
            }
            if (strlen($row['from_name'])) {
                $leadSettings['fromName'] = $row['from_name'];
            }
        }
        return $leadSettings;
    }

    function getAdmEmailSettings()
    {
        $admissionSettings = array('admissionEmail'=>'EXPERT™','admissionSubject'=>'admin@expertinstitute.in','fromName'=>'EXPERT™');
        mysql_set_charset('utf8');
        $sqlEmail = "SELECT setting_group,setting_value,from_name FROM email_settings WHERE setting_group IN('admissionEmail','admissionSubject') AND status=1";
        $result = mysql_query($sqlEmail);
        while ($row = mysql_fetch_assoc($result)) {
            if ($row['setting_group'] == 'admissionSubject') {
                $admissionSettings['admissionSubject'] = $row['setting_value'];
            }
            if ($row['setting_group'] == 'admissionEmail') {
                $admissionSettings['admissionEmail'] = $row['setting_value'];
            }
            if (strlen($row['from_name'])) {
                $admissionSettings['fromName'] = $row['from_name'];
            }
        }
        return $admissionSettings;
    }
}

